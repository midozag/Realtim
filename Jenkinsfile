pipeline {
    agent any
    environment {
        SONAR_SCANNER = tool 'SonarScanner'
        IMAGE_NAME = "zag2020/realtime_app"
        IMAGE_TAG = "${BUILD_NUMBER}"
        IMAGE_FULL = "${IMAGE_NAME}:${IMAGE_TAG}"
    }
    stages {
        stage('Git Checkout') {
            steps {
                git(
                    branch: 'main',
                    credentialsId: 'github-creds',
                    url: 'https://github.com/midozag/Realtim.git'
                )
            }
        }
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh """
                        ${SONAR_SCANNER}/bin/sonar-scanner \
                          -Dsonar.projectKey=TaskApp \
                          -Dsonar.projectName=TaskApp \
                          -Dsonar.sources=app,resources/js \
                          -Dsonar.exclusions=vendor/**,node_modules/**,public/build/**,tests/**,storage/**,bootstrap/cache/**
                    """
                }
            }
        }
        stage('Quality Gate') {
            steps {
                waitForQualityGate abortPipeline: true
            }
        }
        stage('Trivy FS Scan') {
            steps {
                sh """
                    trivy fs \
                      --format table \
                      --exit-code 0 \
                      --severity HIGH,CRITICAL \
                      --output trivy-fs-report.html \
                      .
                """
            }
        }
        stage('Docker Build') {
            steps {
                sh """
                    docker build \
                      -t ${IMAGE_FULL} \
                      -t ${IMAGE_NAME}:latest \
                      .
                """
            }
        }
        stage('Trivy Image Scan') {
            steps {
                sh """
                    trivy image \
                      --format table \
                      --exit-code 0 \
                      --severity HIGH,CRITICAL \
                      --output trivy-image-report.html \
                      ${IMAGE_FULL}
                """
            }
        }
        stage('Docker Push') {
            steps {
                 withCredentials([usernamePassword(
                    credentialsId: 'dockerhub-creds',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    sh """
                        echo \$DOCKER_PASS | docker login -u \$DOCKER_USER --password-stdin
                        docker push ${IMAGE_FULL}
                        docker push ${IMAGE_NAME}:latest
                        docker logout
                    """
                }
            }
        }
        stage('Deploy to Kubernetes') {
            steps {
                withKubeConfig(credentialsId: 'k8s-config') {
                    sh """
                        # Replace IMAGE_PLACEHOLDER with real image tag
                        sed -i 's|IMAGE_PLACEHOLDER|${IMAGE_FULL}|g' k8s/app/app-deployment.yaml

                        # Apply ConfigMap and app manifests
                        kubectl apply -f k8s/app/app-configmap.yaml
                        kubectl apply -f k8s/app/app-deployment.yaml
                        kubectl apply -f k8s/app/app-service.yaml

                        # Wait for rolling update to complete
                        kubectl rollout status deployment/laravel-app --timeout=180s

                        # Show current status
                        echo "── Pods ──────────────────────────────"
                        kubectl get pods
                        echo "── Services ──────────────────────────"
                        kubectl get services
                    """
                }
            }
        }
    }
    post {
        success {
            echo "Pipeline succeeded — app deployed to Kubernetes"
            sh """
                docker rmi ${IMAGE_FULL} || true
                docker rmi ${IMAGE_NAME}:latest || true
            """
        }
        failure {
            echo "Pipeline failed"
        }
    }
}