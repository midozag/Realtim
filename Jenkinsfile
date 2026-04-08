pipeline {
    agent any
    environment {
        SONAR_SCANNER = tool 'SonarScanner'
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
    }
}