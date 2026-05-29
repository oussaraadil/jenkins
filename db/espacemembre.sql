CREATE DATABASE IF NOT EXISTS EspaceMembreDB;
USE EspaceMembreDB;

CREATE TABLE Membre (
    Matricule INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Adresse VARCHAR(200) NOT NULL,
    Tel VARCHAR(20) NOT NULL
);

CREATE TABLE Cotisation (
    NumCotis INT AUTO_INCREMENT PRIMARY KEY,
    DateCotis DATE NOT NULL,
    Mois VARCHAR(20) NOT NULL,
    Motif ENUM('Inscription','Mensualite') NOT NULL,
    Montant DECIMAL(10,2) NOT NULL,
    Matricule INT NOT NULL,
    FOREIGN KEY (Matricule) REFERENCES Membre(Matricule) ON DELETE CASCADE
);
