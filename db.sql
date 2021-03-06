CREATE TABLE COUNTRIES 
(
  CID INTEGER NOT NULL 
, NAME VARCHAR2(50) NOT NULL 
, PRIMARY KEY ( CID )
, CONSTRAINT unique_country_name UNIQUE ( NAME )
);

CREATE TABLE IOC_CODE 
(
  ICID INTEGER NOT NULL 
, CODE CHAR(3) NOT NULL 
, CID INTEGER NOT NULL
, PRIMARY KEY ( ICID )
, FOREIGN KEY ( CID ) REFERENCES COUNTRIES( CID ) ON DELETE CASCADE
, CONSTRAINT unique_ioc_code_name UNIQUE ( CODE )
);

CREATE TABLE SPORTS 
(
  SID INTEGER NOT NULL 
, NAME VARCHAR2(100) NOT NULL 
, PRIMARY KEY ( SID )
, CONSTRAINT unique_sport_name UNIQUE ( NAME )
);

CREATE TABLE ATHLETES 
(
  AID INTEGER NOT NULL 
, NAME VARCHAR2(200) NOT NULL 
, PRIMARY KEY ( AID )
);

CREATE TABLE DISCIPLINES 
(
  DID INTEGER NOT NULL 
, NAME VARCHAR2(200) NOT NULL 
, SID INTEGER NOT NULL
, PRIMARY KEY ( DID )
, FOREIGN KEY ( SID ) REFERENCES SPORTS( SID )
, CONSTRAINT unique_discipline UNIQUE ( NAME, SID )
);

CREATE TABLE GAMES 
(
  GID INTEGER NOT NULL 
, YEAR INTEGER NOT NULL 
, TYPE VARCHAR2(50) NOT NULL 
, HOST_CITY VARCHAR2(200) NOT NULL 
, CID INTEGER NOT NULL 
, PRIMARY KEY ( GID )
, FOREIGN KEY ( CID ) REFERENCES COUNTRIES( CID )
, CONSTRAINT unique_game UNIQUE ( YEAR, TYPE )
);

CREATE TABLE EVENTS 
(
  EID INTEGER NOT NULL 
, GID INTEGER NOT NULL
, DID INTEGER NOT NULL
, PRIMARY KEY ( EID )
, FOREIGN KEY ( GID ) REFERENCES GAMES( GID )
, FOREIGN KEY ( DID ) REFERENCES DISCIPLINES( DID )
, CONSTRAINT unique_event UNIQUE ( GID, DID )
);

CREATE TABLE PARTICIPANTS 
(
  PID INTEGER NOT NULL 
, AID INTEGER NOT NULL
, CID INTEGER
, GID INTEGER NOT NULL
, SID INTEGER NOT NULL
, PRIMARY KEY ( PID )
, FOREIGN KEY ( AID ) REFERENCES ATHLETES(AID)
, FOREIGN KEY ( CID ) REFERENCES COUNTRIES(CID)
, FOREIGN KEY ( GID ) REFERENCES GAMES(GID)
, FOREIGN KEY ( SID ) REFERENCES SPORTS(SID)
, CONSTRAINT unique_participant UNIQUE ( AID, CID, GID, SID )
);

CREATE TABLE MEDALS 
(
  MID INTEGER NOT NULL
, TYPE VARCHAR2(50) NOT NULL 
, CID INTEGER
, EID INTEGER NOT NULL
, AID INTEGER NOT NULL
, PRIMARY KEY ( MID )
, FOREIGN KEY ( CID ) REFERENCES COUNTRIES(CID)
, FOREIGN KEY ( AID ) REFERENCES ATHLETES(AID)
, FOREIGN KEY ( EID ) REFERENCES EVENTS(EID)
, CONSTRAINT unique_medalist UNIQUE ( CID, EID, AID )
);
