<?php
namespace App\Models;

    abstract class ApplicationConstants {
        public static string $APPLICATION_DATA_ERROR = "Errore nella ricezione dei dati. Riprovare più tardi!";
        public static string $APPLICATION_DATABASE_FAILED = "Errore nell'inserimento dei dati!";
        public static string $APPLICATION_INCORRECT_PARAMETERS = "Parametri di registrazione incorretti!";
        public static string $APPLICATION_PASSWORD_MISMATCH = "Le due password devono essere uguali!";
        public static string $USER_ALREADY_EXISTS = "Utente già registrato!";
        public static string $USER_REGISTERED = "Utente registrato!";
        public static int $TOKEN_EXPIRY_SECONDS = 7200;
        public static string $FORM_UPLOAD_SUCCESSFULLY = "Dati aggiornati con successo!";
        public static string $FORM_UPLOAD_FAILED = "Errore nell'aggiornamento dei dati!";
        public static string $LOGOUT = "Logout effettuato con successo!";
        public static string $DRIVER_LICENSE_CREATED = "Patente Registrata Con Successo!";
        public static string $DRIVER_LICENSE_CREATION_FAILED = "Patente non registrata, riprova!";
        public static string $MISSING_DRIVERLICENSE = "Devi prima registrare la patente di guida!";
        public static string $EXISTING_DRIVERLICENSE = "Patente di guida già registrata!";
        public static string $NULL_ACCOUNT_DRIVER = "Account guidatore non presente!";
        public static string $APPLICATION_INCORRECT_METHOD = "Impossibile accedere a questa richiesta!";
        public static int $CAR_BASIC_CHUNK = 10;
        public static string $CANNOT_VIEW_PAGE = "Non hai il permesso di accedere a questa pagina";
        public static string $CANNOT_EXECUTE = "Non hai il permesso di eseguire questa operazione";
        public static string $DELETE_SUCCESSFULLY = "Cancellazione effetuata!";
        public static string $DELETE_FAILED = "Impossibile cancellare!";
        public static string $UPDATE_SUCCESSFULLY = "Aggiornamento effetuato!";
        public static string $UPDATE_FAILED = "Impossibile aggiornare!";
        public static string $CREATE_SUCCESSFULLY = "Creazione effetuata!";
        public static string $CREATE_FAILED = "Impossibile creare!";
        public static string $NO_CHANGES_MADE = "Nessuna modifica apportata!";
    }