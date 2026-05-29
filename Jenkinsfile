pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "cotisations-uai"
        COMPOSE_INTERACTIVE_NO_CLI = "1"
    }

    stages {
        stage('Nettoyage Initial') {
            steps {
                echo 'Nettoyage des anciens conteneurs pour repartir sur une base propre...'
                // On s'assure que rien ne tourne déjà sur le port 8081
                sh '/usr/local/bin/docker-compose down --remove-orphans'
            }
        }

        stage('Construction (Build)') {
            steps {
                echo 'Construction des images (PHP Custom)...'
                // Construit l'image définie dans ton dossier ./php/Dockerfile
                sh '/usr/local/bin/docker-compose build'
            }
        }

        stage('Déploiement Local') {
            steps {
                echo 'Lancement du site de cotisations...'
                // Lance les services (db, php, nginx) en arrière-plan (-d)
                sh '/usr/local/bin/docker-compose up -d'
            }
        }

        stage('Vérification') {
            steps {
                echo 'Vérification du statut des services...'
                sh '/usr/local/bin/docker-compose ps'
                echo "Le site devrait être accessible sur : http://localhost:8081"
            }
        }
    }

    post {
        failure {
            echo "Le déploiement a échoué. Vérification des logs..."
            sh 'docker-compose logs --tail=20'
        }
        success {
            echo "Déploiement terminé avec succès !"
        }
    }
}