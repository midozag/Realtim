pipeline {
    agent any
    environment {
        SONAR_SCANNER = tool 'SonarScanner'
        IMAGE_NAME = "zag2020/realtime_app"
        IMAGE_TAG = "${BUILD_NUMBER}"
        IMAGE_FULL = "${IMAGE_NAME}:${IMAGE_TAG}"
        DOCKERHUB_PASSWORD = credentials('DOCKERHUB_PASSWORD')
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
    }
}