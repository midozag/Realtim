pipeline {
    agent any

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
    }
}