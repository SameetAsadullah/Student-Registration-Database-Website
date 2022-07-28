--===============================================================================================================================
--===============================================================================================================================
-------------------------------------------------[ MAIN DDL TABLE CREATION CODE ]------------------------------------------------
--===============================================================================================================================
--===============================================================================================================================

--------------------------------------------------------------------------------
----------------------------[ GUARDIAN TABLE ]----------------------------------
--------------------------------------------------------------------------------
--Creating the Guardian Table
CREATE TABLE GUARDIAN
(
    ID          INT             NOT NULL,
    CNIC        CHAR(13)        NOT NULL,
    NAME        VARCHAR(15)     NOT NULL,
    GENDER      CHAR(1)         NOT NULL,
    CONTACT     VARCHAR(15)     NOT NULL,
    EMAIL       VARCHAR(30),    
    ADDRESS     VARCHAR(50),
    IS_PARENT   INT             NOT NULL,       
    RELATION    VARCHAR(15),
    IS_STAFF    INT
);
--Creating the Primary Key ID
ALTER TABLE GUARDIAN
ADD CONSTRAINTS
    PK_GUARDIAN   PRIMARY KEY (ID);

--Creating a sequence for primary key
CREATE SEQUENCE SEQ_GUARDIAN
MINVALUE 1026
START WITH 1026
INCREMENT BY 1
CACHE 10;

-------------------------------------------------
-------------[ GUARDIAN LOG TABLE ]--------------
-------------------------------------------------

CREATE TABLE GUARDIAN_LOG
(
    ID          INT             NOT NULL,
    RECORD      INT             NOT NULL,
    CNIC        CHAR(13)        NOT NULL,
    NAME        VARCHAR(15)     NOT NULL,
    GENDER      CHAR(1)         NOT NULL,
    CONTACT     VARCHAR(15)     NOT NULL,
    EMAIL       VARCHAR(30),    
    ADDRESS     VARCHAR(50),
    IS_PARENT   INT         NOT NULL,       
    RELATION    VARCHAR(15),
    IS_STAFF    INT
);

--Creating a trigger for auto-generating the record number
CREATE OR REPLACE TRIGGER GUARDIAN_LOG_RECORD
BEFORE INSERT ON GUARDIAN_LOG
FOR EACH ROW
DECLARE
    record INT := 0;
BEGIN
    SELECT MAX(RECORD) + 1 
    INTO record
    FROM GUARDIAN_LOG
    WHERE ID = :NEW.ID;

    IF record IS NULL THEN
        :NEW.RECORD := 1;
    ELSE
        :NEW.RECORD := record;
    END IF;        
END;
/

--Creating a trigger for auto-generating primary key
--This trigger also handles the relation status if the guardian is a parent
CREATE OR REPLACE TRIGGER GUARDIAN_KEY
BEFORE INSERT ON GUARDIAN
FOR EACH ROW
DECLARE
    M   VARCHAR(15);
    F   VARCHAR(15);
BEGIN
    SELECT SEQ_GUARDIAN.nextval
    INTO:NEW.ID
    FROM DUAL;
    
    M := 'MOTHER';
    F := 'FATHER';
    IF :NEW.IS_PARENT = 1 THEN
        IF :NEW.GENDER = 'M' THEN
            :NEW.RELATION := F;
        ELSE
            :NEW.RELATION := M;
        END IF;
    END IF;
END;
/

CREATE OR REPLACE TRIGGER GUARDIAN_INSERT
AFTER INSERT OR UPDATE ON GUARDIAN
FOR EACH ROW
BEGIN
    INSERT INTO GUARDIAN_LOG (ID,NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF)
    VALUES(:NEW.ID,:NEW.NAME,:NEW.GENDER,:NEW.CNIC,:NEW.CONTACT,:NEW.EMAIL,:NEW.ADDRESS,:NEW.IS_PARENT,:NEW.RELATION,:NEW.IS_STAFF);
END;
/

--------------------------------------------------------------------------------
----------------------------[ STUDENT TABLE ]----------------------------------
--------------------------------------------------------------------------------

--Creating the Student table
CREATE TABLE STUDENT
(
    ID              INT             NOT NULL,
    ROLL_NO         INT             NOT NULL,
    NAME            VARCHAR(15)     NOT NULL,
    DOB             DATE,
    DATE_ADMITTED   DATE,
    GENDER          CHAR(1),
    AGE             NUMBER(*,1),
    FATHER_ID       INT,
    MOTHER_ID       INT,
    GUARDIAN_ID     INT
);

--Creating the Primary Key and Foreign Keys
ALTER TABLE STUDENT
ADD CONSTRAINTS
    PK_STUDENT  PRIMARY KEY (ID);

ALTER TABLE STUDENT
ADD CONSTRAINTS    
    FK_FATHER   FOREIGN KEY (FATHER_ID) REFERENCES GUARDIAN(ID);

ALTER TABLE STUDENT
ADD CONSTRAINTS 
    FK_MOTHER   FOREIGN KEY (MOTHER_ID) REFERENCES GUARDIAN(ID);

ALTER TABLE STUDENT
ADD CONSTRAINTS  
    FK_GUARDIAN FOREIGN KEY (GUARDIAN_ID) REFERENCES GUARDIAN(ID);

--Creating a sequence for primary key
CREATE SEQUENCE SEQ_STUDENT
MINVALUE 1000
START WITH 1000
INCREMENT BY 1
CACHE 10;

CREATE SEQUENCE SEQ_STUDENT_ROLL
MINVALUE 2000
START WITH 2000
INCREMENT BY 1
CACHE 10;

--========================================
--------------[ STUDENT LOG ]-------------
--========================================
CREATE TABLE STUDENT_LOG
(
    ID              INT             NOT NULL,
    RECORD          INT             NOT NULL,
    ROLL_NO         INT             NOT NULL,
    NAME            VARCHAR(15)     NOT NULL,
    DOB             DATE,
    DATE_ADMITTED   DATE,
    GENDER          CHAR(1),
    AGE             NUMBER(*,1),
    FATHER_ID       INT,
    MOTHER_ID       INT,
    GUARDIAN_ID     INT
);

--Creating a trigger for auto-generating the record number
CREATE OR REPLACE TRIGGER STUDENT_LOG_RECORD
BEFORE INSERT ON STUDENT_LOG
FOR EACH ROW
DECLARE
    record INT := 0;
BEGIN
    SELECT MAX(RECORD) + 1 
    INTO record
    FROM STUDENT_LOG
    WHERE ID = :NEW.ID;

    IF record IS NULL THEN
        :NEW.RECORD := 1;
    ELSE
        :NEW.RECORD := record;
    END IF;        
END;
/

--Creating a trigger for auto-generating primary key
--This trigger also handles the Age thing
CREATE OR REPLACE TRIGGER STUDENT_KEY
BEFORE INSERT ON STUDENT
FOR EACH ROW
DECLARE
    today   DATE;
BEGIN
    SELECT SEQ_STUDENT.nextval
    INTO:NEW.ID
    FROM DUAL;

    SELECT SEQ_STUDENT_ROLL.nextval
    INTO:NEW.ROLL_NO
    FROM DUAL;

    SELECT SYSDATE
    INTO today
    FROM DUAL;

    :NEW.AGE := (today - :NEW.DOB) / 365;
END;
/

--Creating a trigger to handle Student Log
CREATE OR REPLACE TRIGGER STUDENT_INSERT
AFTER INSERT OR UPDATE ON STUDENT
FOR EACH ROW
BEGIN
    INSERT INTO STUDENT_LOG(ID, ROLL_NO, NAME, DOB, GENDER, AGE, FATHER_ID, MOTHER_ID, GUARDIAN_ID)
    VALUES(:NEW.ID, :NEW.ROLL_NO, :NEW.NAME, :NEW.DOB, :NEW.GENDER, :NEW.AGE, :NEW.FATHER_ID, :NEW.MOTHER_ID, :NEW.GUARDIAN_ID);
END;
/

--------------------------------------------------------------------------------
----------------------------[ COURSE TABLE ]------------------------------------
--------------------------------------------------------------------------------
--Creating the Course Table
CREATE TABLE COURSE
(
    COURSE_ID   INT           NOT NULL,
    NAME        VARCHAR2(30)  NOT NULL
);

--Creating the Primary Key COURSE_ID
ALTER TABLE COURSE
ADD CONSTRAINTS
    PK_COURSE_ID PRIMARY KEY (COURSE_ID);

--Creating a sequence for primary key
CREATE SEQUENCE COURSE_SEQUENCE
    MINVALUE 1010
    START WITH 1010
    INCREMENT BY 1
    CACHE 10;

--Creating a trigger for auto-generating primary key
CREATE OR REPLACE TRIGGER COURSE_ID_ON_INSERT
    BEFORE INSERT ON COURSE
    FOR EACH ROW
    BEGIN
        SELECT COURSE_SEQUENCE.NEXTVAL
        INTO :NEW.COURSE_ID
        FROM DUAL;
    END;
    /

--======================================[ INSERTING INTO COURSE ]====================================
INSERT ALL
    INTO COURSE (NAME) VALUES ('DEPRESSION')
    INTO COURSE (NAME) VALUES ('FAMILY')
    INTO COURSE (NAME) VALUES ('MANAGEMENT')
    INTO COURSE (NAME) VALUES ('CREATIVE WRITING')  
SELECT * FROM DUAL;

--------------------------------------------------------------------------------
----------------------------[ CLASS TABLE ]-------------------------------------
--------------------------------------------------------------------------------
--Creating the Class Table
CREATE TABLE CLASS
(
    CLASS_ID    INT     NOT NULL,
    COURSE_ID   INT     NOT NULL,
    CLASS_NO    INT     NOT NULL,
    SECTION     CHAR(1),     
    NAME        VARCHAR2(20),
    GROUPING    VARCHAR2(3)    
);
  
--Creating the Primary Key CLASS_ID
ALTER TABLE CLASS
ADD CONSTRAINTS
    PK_CLASS_ID PRIMARY KEY (CLASS_ID);

--Creating a Foreign Key COURSE_ID
ALTER TABLE CLASS
ADD CONSTRAINTS
    FK_COURSE_ID FOREIGN KEY (COURSE_ID) REFERENCES COURSE (COURSE_ID);
  
--Creating a sequence for primary key
CREATE SEQUENCE CLASS_SEQUENCE
    MINVALUE 1050
    START WITH 1050
    INCREMENT BY 1
    CACHE 10;

--Creating a trigger for auto-generating primary key
CREATE OR REPLACE TRIGGER CLASS_ID_ON_INSERT
    BEFORE INSERT ON CLASS
    FOR EACH ROW
    BEGIN
        SELECT CLASS_SEQUENCE.NEXTVAL
        INTO :NEW.CLASS_ID
        FROM DUAL;
    END;
    /

--Creating a trigger for auto-generating classes whenever a new course will be offered
CREATE OR REPLACE TRIGGER INSERT_CLASSES
    AFTER INSERT ON COURSE
    FOR EACH ROW
    DECLARE
        V_COURSE_ID NUMBER;
    BEGIN
        SELECT COURSE_SEQUENCE.CURRVAL INTO V_COURSE_ID FROM DUAL;
        INSERT INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (V_COURSE_ID, 1, 'A', 'MQ', 'NO');
        INSERT INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (V_COURSE_ID, 2, 'A', 'MJ', 'NO');
        INSERT INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (V_COURSE_ID, 3, 'A', 'MK', 'NO');
        INSERT INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (V_COURSE_ID, 4, 'A', 'ML', 'NO');
        INSERT INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (V_COURSE_ID, 5, 'A', 'MY', 'NO');
    END;
    /

--------------------------------------------------------------------------------
----------------------------[ CHALLAN TABLE ]-----------------------------------
--------------------------------------------------------------------------------
--Creating the challan table
CREATE TABLE CHALLAN 
(
    COURSE_ID     INT      NOT NULL,
    STUDENT_ID    INT      NOT NULL,   
    CHALLAN_NO    INT      NOT NULL,
    REG_DATE      DATE,      
    FEE           INT,
    DISCOUNT      INT,
    PAID_STATUS   NUMBER(1),
    FINAl_AMOUNT  INT,
    PRIMARY KEY(CHALLAN_NO)
);

ALTER TABLE CHALLAN
    ADD CONSTRAINTS FK_COURSE_ID_CHALLAN
    FOREIGN KEY (COURSE_ID) REFERENCES COURSE(COURSE_ID);

ALTER TABLE CHALLAN
    ADD CONSTRAINTS FK_STUDENT_ID_CHALLAN
    FOREIGN KEY (STUDENT_ID) REFERENCES STUDENT(ID);    

--Creating sequence for primary key
CREATE SEQUENCE CHALLAN_GEN_SEQ
    MINVALUE 3000
    START WITH 3000
    INCREMENT BY 1
    CACHE 10;

--Creating trigger for auto-generating primary key
CREATE OR REPLACE TRIGGER CHALLAN_TRIGGER_1
BEFORE INSERT ON CHALLAN
FOR EACH ROW
DECLARE
    age             INT;
    d               DATE;
    class           INT;
    sibling         INT;   
    discount        INT;
    motherStaff     INT;
    fatherStaff     INT;
BEGIN
    --Assigning Challan Number
    :NEW.CHALLAN_NO := CHALLAN_GEN_SEQ.NEXTVAL;

    --Getting the DOB of the Student
    SELECT DOB
    INTO d
    FROM STUDENT
    WHERE ID = :NEW.STUDENT_ID;

    --Calculating Age
    age := (:NEW.REG_DATE - d) / 365;

    --Determining Class
    IF (age <= 4) THEN
        class := 1;
    ELSIF (age <= 6) THEN
        class := 2;
    ELSIF (age <= 9) THEN
        class := 3;
    ELSIF (age <= 12) THEN
        class := 4;
    ELSE
        class := 5;
    END IF;     

    --Assigning Fee
    :NEW.FEE := 1000 * class;
    :NEW.DISCOUNT := 0;

    --Finding Sibling Discount
    SELECT COUNT(*)
    INTO sibling
    FROM STUDENT
    WHERE MOTHER_ID = (SELECT MOTHER_ID FROM STUDENT WHERE ID = :NEW.STUDENT_ID)
    AND FATHER_ID = (SELECT FATHER_ID FROM STUDENT WHERE ID = :NEW.STUDENT_ID);

    IF (sibling >= 3) THEN
        :NEW.DISCOUNT := (:NEW.FEE / 100) * 20;
    END IF;

    --Getting the Staff Discount
    SELECT G.IS_STAFF
    INTO motherStaff
    FROM GUARDIAN G, STUDENT S
    WHERE S.MOTHER_ID = G.ID
    AND S.ID = :NEW.STUDENT_ID;

    SELECT G.IS_STAFF
    INTO fatherStaff
    FROM GUARDIAN G, STUDENT S
    WHERE S.FATHER_ID = G.ID
    AND S.ID = :NEW.STUDENT_ID;

    IF (fatherStaff = 1) OR (motherStaff = 1) THEN
        :NEW.DISCOUNT := :NEW.FEE;
        :NEW.PAID_STATUS := 1;
    END IF;

    --Final Amount
    :NEW.FINAl_AMOUNT := :NEW.FEE - :NEW.DISCOUNT;
END;
/


--------------------------------------------------------------------------------
----------------------------[ ACCOMPANY TABLE ]-----------------------------------
--------------------------------------------------------------------------------
--Creating the accompany table
CREATE TABLE ACCOMPANY 
(
    COURSE_ID     INT     NOT NULL,
    CLASS_ID      INT     NOT NULL,
    STUDENT_ID    INT     NOT NULL,
    GUARDIAN_ID   INT     NOT NULL,
    A_DATE        DATE,
    REASON        VARCHAR(50),
    PREGNANT      NUMBER
);

--Creating the composite keys
ALTER TABLE ACCOMPANY
ADD CONSTRAINTS
    PK_ACCOMPANY PRIMARY KEY (COURSE_ID, CLASS_ID, STUDENT_ID, GUARDIAN_ID);

--Creating Foreign Key COURSE_ID
ALTER TABLE ACCOMPANY
ADD CONSTRAINTS
    FK_COURSE_ID_COURSE FOREIGN KEY (COURSE_ID) REFERENCES COURSE (COURSE_ID);

--Creating Foreign Key CLASS_ID
ALTER TABLE ACCOMPANY
ADD CONSTRAINTS
    FK_CLASS_ID FOREIGN KEY (CLASS_ID) REFERENCES CLASS (CLASS_ID);

--Creating Foreign Key STUDENT_ID
ALTER TABLE ACCOMPANY
ADD CONSTRAINTS
    FK_STUDENT_ID FOREIGN KEY (STUDENT_ID) REFERENCES STUDENT (ID);

--Creating Foreign Key GUARDIAN_ID
ALTER TABLE ACCOMPANY
ADD CONSTRAINTS
    FK_GUARDIAN_ID FOREIGN KEY (GUARDIAN_ID) REFERENCES GUARDIAN (ID);

--------------------------------------------------------------------------------
----------------------------[ REGISTERED TABLE ]--------------------------------
--------------------------------------------------------------------------------
--Creating the REGISTERED Table
CREATE TABLE REGISTERED
(
    COURSE_ID   INT     NOT NULL,
    STUDENT_ID  INT     NOT NULL,
    CLASS_ID    INT     NOT NULL,
    REG_DATE    DATE,
    CHALLAN_NO  INT
);

--Creating Composite Keys
ALTER TABLE REGISTERED
ADD CONSTRAINTS
    CK_REGISTERED PRIMARY KEY (COURSE_ID, STUDENT_ID, CLASS_ID);

--Creating Foreign Key COURSE_ID
ALTER TABLE REGISTERED
ADD CONSTRAINTS
    FK_REGISTERED_COURSE_ID FOREIGN KEY (COURSE_ID) REFERENCES COURSE (COURSE_ID);

--Creating Foreign Key STUDENT_ID
ALTER TABLE REGISTERED
ADD CONSTRAINTS
    FK_REGISTERED_STUDENT_ID FOREIGN KEY (STUDENT_ID) REFERENCES STUDENT (ID);

--Creating Foreign Key CLASS_ID
ALTER TABLE REGISTERED
ADD CONSTRAINTS
    FK_REGISTERED_CLASS_ID FOREIGN KEY (CLASS_ID) REFERENCES CLASS (CLASS_ID);

---------The Main trigger that handles the insertion of Students 
---------in the registration table. It assigns them the Class 
---------Based on thier age
CREATE OR REPLACE TRIGGER INSERT_REGISTRATION
BEFORE INSERT ON REGISTERED
FOR EACH ROW
DECLARE
    age         NUMBER(9, 1);
    d           DATE;
    course      INT;
    class       INT;
BEGIN
    --Getting the DOB of the Student
    SELECT DOB
    INTO d
    FROM STUDENT
    WHERE ID = :NEW.STUDENT_ID;

    --Calculating Age
    age := (:NEW.REG_DATE - d) / 365;

    --Determining Class
    IF (age <= 4) THEN
        class := 1;
    ELSIF (age <= 6) THEN
        class := 2;
    ELSIF (age <= 9) THEN
        class := 3;
    ELSIF (age <= 12) THEN
        class := 4;
    ELSE
        class := 5;
    END IF;     

    --Finding the Class Number
    SELECT CLASS_ID 
    INTO:NEW.CLASS_ID
    FROM CLASS
    WHERE COURSE_ID = :NEW.COURSE_ID AND CLASS_NO = class AND SECTION = 'A';

    --Picking up the Correct Challan 
    SELECT CHALLAN_NO
    INTO :NEW.CHALLAN_NO
    FROM CHALLAN
    WHERE COURSE_ID = :NEW.COURSE_ID AND STUDENT_ID = :NEW.STUDENT_ID;
END;
/    

---Creating the Class Log Table
CREATE TABLE CLASS_LOG
(
    STUDENT_ID  INT             NOT NULL,
    RECORD      INT             NOT NULL,
    CLASS_NO    INT             NOT NULL,
    SECTION     CHAR(1)         NOT NULL,
    REASON      VARCHAR2(40),
    APPROVED    VARCHAR2(25)
);    

--Creating the Trigger to Handle the Class Change
CREATE OR REPLACE TRIGGER CLASS_INSERT_RECORD
BEFORE INSERT ON CLASS_LOG
FOR EACH ROW
DECLARE
    record      INT;
    course      INT;
    class       INT;
BEGIN
    --Handling the Record Thing
    SELECT MAX(RECORD) + 1 
    INTO record
    FROM CLASS_LOG
    WHERE STUDENT_ID = :NEW.STUDENT_ID;

    IF record IS NULL THEN
        :NEW.RECORD := 1;
    ELSE
        :NEW.RECORD := record;
    END IF;

    --Get the Max Course ID = Current Course
    SELECT MAX(COURSE_ID)
    INTO course
    FROM REGISTERED;

    --Get the corresponding Class ID 
    SELECT CLASS_ID 
    INTO class 
    FROM CLASS 
    WHERE COURSE_ID = course AND CLASS_NO = :NEW.CLASS_NO AND SECTION = :NEW.SECTION;

    --Update the Registered Table
    UPDATE REGISTERED
    SET CLASS_ID = class
    WHERE COURSE_ID = course AND STUDENT_ID = :NEW.STUDENT_ID;
END;
/

--===============================================================================================================================
--===============================================================================================================================
----------------------------------------------------[ MAIN DML INSERTION CODE ]--------------------------------------------------
--===============================================================================================================================
--===============================================================================================================================

--======================================[ INSERTING INTO GUARDIAN ]====================================
INSERT ALL  
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Uzma Ali', 'F', 3201046766462, 03049463254, 'Ali_Uzma21@gmail.com', 'House#23, Street 11, E-11/4, Islamabad', 1, 'Mother', 1)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Imran Mubasshir', 'M', 3534155312341, 03405987412, 'Imran845@gmail.com', 'House#23, Street 11, E-11/4, Islamabad', 1, 'Father', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Irfan Butt', 'M', 3440000423313, 03218469324, 'Irfan4521@gmail.com', 'House# 69, F-11/1, Islamabad', 1, 'Father', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Kabeer Khan', 'M', 3303575131232, 03349658741, 'Kabeer.khan74@gmail.com', 'Flat#45, Shara Building, Rawalpindi', 0, 'Uncle', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Bilal Abbas', 'M', 3413411005378, 03336523145, 'Bilal_ali45@gmail.com', 'House#49, Gulshan-e-Quaid, Rawalpindi', 1, 'Father', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Nadia Khan', 'F', 3525127173516, 03089874563, 'Khan_nadia96@gmail.com', 'House#41, Street#21, G-10/4, Islamabad', 0, 'Aunt', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Sania Ali', 'F', 3740596354896, 03108459631, 'sania_ali41@gmail.com', 'House#49, Gulshan-e-Quaid, Rawalpindi', 1, 'Mother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Jawad Chaudray', 'M', 3658745126984, 03408459745, 'Ch_jawad784@gmail.com', 'House#2, Street#13, G-10/1, Islamabad', 1, 'Father', 1)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Faryal Mehmood', 'F', 3748563214684, 03415796321, 'Mehmood_Fr78@gmail.com', 'House# 69, F-11/1, Islamabad', 1, 'Mother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Atif Ali', 'M', 3750496321542, 03148459632, 'Atifkahn8452@gmail.com', 'House#87, F-11/4, Islamabad', 1, 'Father', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Mohsin Ali', 'M', 3845219632452, 03189455655, 'Mohsin..4512@gmail.com', 'Flat#69, Tower#7, E-11/1, Islamabad', 0, 'Brother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Ayesha Kamran', 'F', 3254196587451, 03208745556, 'AyshKamran87@gmail.com', 'House#100, Street 10, F-7/3, Islamabad', 0, 'Grandmother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Asma Faisal', 'F', 3540296314852, 03215487963, 'asma_faisal99@gmail.com', 'House#2, Street#13, G-10/1, Islamabad', 1, 'Mother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Hamza Ahmed', 'M', 3695432154862, 03245788896, 'Ahmedkhan@gmail.com', 'Flat#52, Tower#9, E-11/2, Islamabad', 0, 'Uncle', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Umme Kalsom', 'F', 3451236957845, 03257884511, 'Kalsoom41@gmail.com', 'House#87, H-11/4, Islamabad', 0, 'Sister', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Mariam Ahmed', 'F', 3451208745632, 03648774512, 'mariaam784@yahoo.com', 'House#21, PWD Block D, Rawalpindi', 0, 'Aunt', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Ahsan Khalid', 'M', 3746598565321, 03239656521, 'Khalid48Ahsan@gmail.com', 'House#14, Satellite town phase 2, Rawalpindi', 1, 'Father', 1)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Momina saeed', 'F', 3452174789654, 03358466966, 'saeed_khan@gmail.com', 'House#87, F-11/4, Islamabad', 1, 'Mother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Mahrukh Khan', 'F', 3746532974213, 03339463333, 'khan_mah95@gmail.com', 'House#14, Satellite town phase 2, Rawalpindi', 1, 'Mother', 0)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Raheela Malik', 'F', 3750129451213, 03208745566, 'Raheel282000@gmail.com', 'House#121, Gulshan-e-Quaid, Rawalpindi', 1, 'Mother', 1)
    INTO GUARDIAN (NAME,GENDER,CNIC,CONTACT,EMAIL,ADDRESS,IS_PARENT,RELATION,IS_STAFF) VALUES ('Moiz Jibran', 'M', 3740521369875, 03219693320, 'Jibran_moiz@gmail.com', 'House#121, Gulshan-e-Quaid, Rawalpindi', 1, 'Father', 0)
SELECT * FROM dual;

--===============================[ UPDATING THE GUARDIAN TO CREATE LOG ]========================================

UPDATE GUARDIAN
SET NAME = 'Raheila Malik'
WHERE ID = 1045;

UPDATE GUARDIAN 
SET CONTACT = 03457896542
WHERE ID = 1045;

UPDATE GUARDIAN 
SET CONTACT = 03478965214
WHERE ID = 1035;

UPDATE GUARDIAN 
SET ADDRESS = 'FLAT#21, TOWER#9, E-11/1, ISLAMABAD'
WHERE ID =1036;

UPDATE GUARDIAN 
SET CONTACT = 03214574121
WHERE ID = 1028;

UPDATE GUARDIAN 
SET EMAIL = 'Raheila282000@gmail.com'
WHERE ID = 1045;

UPDATE GUARDIAN 
SET NAME = 'Bilal Abbass'
WHERE ID = 1030;

UPDATE GUARDIAN
SET CNIC = 3746532974214
WHERE ID = 1044;

UPDATE GUARDIAN
SET EMAIL = 'Irfan_Butt@gamil.com'
WHERE ID = 1028;

UPDATE GUARDIAN
SET NAME = 'Saniya Ali'
WHERE ID = 1032;

UPDATE GUARDIAN
SET CONTACT = 03122241536
WHERE ID = 1042;

UPDATE GUARDIAN
SET ADDRESS = 'Flat#98, Sahara Buildings, Rawalpindi'
WHERE ID = 1029;


--======================================[ INSERTING INTO STUDENT ]====================================
INSERT ALL
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Ali ', 'M', TO_DATE('2010-04-07 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2018-03-25 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1026, 1027, 1029)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Usman', 'M', TO_DATE('2013-05-21 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-05 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1026, 1027, 1029)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Kashaf', 'F', TO_DATE('2008-01-26 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2018-02-15 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1026, 1027, 1029)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Maria', 'F', TO_DATE('2016-12-09 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2020-03-29 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1032, 1030, 1031)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Nabeel', 'M', TO_DATE('2011-06-08 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2018-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1034, 1028, 1036)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Junaid', 'M', TO_DATE('2006-09-12 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2019-04-02 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1038, 1033, 1037)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Tayyab', 'M', TO_DATE('2007-11-12 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2019-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1043, 1035, 1039)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Hassan', 'M', TO_DATE('2007-08-09 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-03-31 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1044, 1042, 1040)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Hussain', 'M', TO_DATE('2008-09-10 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2018-03-31 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1044, 1042, 1040)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Ana ', 'F', TO_DATE('2014-01-01 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2018-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1045, 1046, 1041)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Asma', 'F', TO_DATE('2010-02-02 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1032, 1030, 1031)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Amar ', 'M', TO_DATE('2015-01-21 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2019-04-07 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1045, 1046, 1041)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Fatima', 'F', TO_DATE('2008-07-09 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2020-03-20 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1034, 1028, 1036)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Sohail', 'M', TO_DATE('2009-04-02 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1038, 1033, 1037)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Maaz', 'M', TO_DATE('2013-03-21 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1032, 1030, 1031)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Ibrahim', 'M', TO_DATE('2012-1-21 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1032, 1030, 1031)
    INTO STUDENT (NAME,GENDER,DOB,DATE_ADMITTED,MOTHER_ID,FATHER_ID,GUARDIAN_ID) VALUES ('Zainab', 'F', TO_DATE('2010-12-09 00:00:00','yyyy/mm/dd hh24:mi:ss'),TO_DATE('2017-04-01 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1043, 1035, 1039)
SELECT * FROM dual;

--===============================[ UPDATING THE STUDENT TO CREATE LOG ]========================================

UPDATE STUDENT
SET NAME = 'Hasan'
WHERE ID = 1005;

UPDATE STUDENT
SET NAME = 'Ammar'
WHERE ID = 1006;

UPDATE STUDENT
SET DOB = TO_DATE('2008-07-08 00:00:00','yyyy/mm/dd hh24:mi:ss')
WHERE ID = 1007;

UPDATE STUDENT
SET DOB = TO_DATE('2013-04-21 00:00:00','yyyy/mm/dd hh24:mi:ss')
WHERE ID = 1009;

UPDATE STUDENT
SET NAME = 'Mariya'
WHERE ID = 1002;

--======================================[ INSERTING INTO CLASS ]=====================================
INSERT ALL
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 1, 'A', 'MQ', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 2, 'A', 'MJ-GIRLS', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 2, 'B', 'MJ-BOYS', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 3, 'A', 'MK', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 4, 'A', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 5, 'A', 'MY-GIRLS', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1010, 5, 'B', 'MY-BOYS', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 1, 'A', 'MQ', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 1, 'B', 'MQ', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 2, 'A', 'MJ', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 3, 'A', 'MK', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 3, 'B', 'MK', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 4, 'A', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 4, 'B', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1011, 5, 'A', 'MY', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 1, 'A', 'MQ', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 2, 'A', 'MJ', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 3, 'A', 'MK-BOYS', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 3, 'B', 'MK-GIRLS', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 4, 'A', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 4, 'B', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1012, 5, 'A', 'MY', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 1, 'A', 'MQ', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 2, 'A', 'MJ', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 2, 'B', 'MJ', 'YES')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 3, 'A', 'MK', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 3, 'B', 'MK', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 4, 'A', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 4, 'B', 'ML', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 5, 'A', 'MY', 'NO')
    INTO CLASS (COURSE_ID,CLASS_NO,SECTION,NAME,GROUPING) VALUES (1013, 5, 'B', 'MY', 'NO')
SELECT * FROM DUAL;

--======================================[ INSERTING INTO CHALLAN ]=====================================
INSERT ALL
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1005, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1010, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1009, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1008, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1015, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1016, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1010, 1011, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1012, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1013, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1002, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1014, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1005, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1010, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1009, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1011, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1008, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 0)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1015, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1011, 1016, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 0)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1004, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1003, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1006, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1012, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1002, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1000, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1014, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1010, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1009, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1011, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1008, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1015, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1012, 1016, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1007, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1001, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1004, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1003, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1006, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1012, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1002, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1014, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1010, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1009, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1011, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1015, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
    INTO CHALLAN (COURSE_ID,STUDENT_ID,REG_DATE,PAID_STATUS) VALUES (1013, 1016, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'), 1)
SELECT * FROM dual;

--======================================[ INSERTING INTO REGISTERED AND CLASS LOGS]=====================================
INSERT ALL
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1005, TO_DATE('2017-02-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1010, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1009, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1008, TO_DATE('2017-04-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1015, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1016, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1010, 1011, TO_DATE('2017-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
SELECT * FROM dual;

INSERT INTO CLASS_LOG(STUDENT_ID, CLASS_NO, SECTION, REASON, APPROVED) VALUES(1005, 4, 'A', 'Registered', 'Management');
INSERT INTO CLASS_LOG(STUDENT_ID, CLASS_NO, SECTION, REASON, APPROVED) VALUES(1005, 5, 'A', 'Age', 'Management');

INSERT ALL
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1012, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1013, TO_DATE('2018-04-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1002, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1014, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1005, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1010, TO_DATE('2018-03-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1009, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1011, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1008, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1015, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1011, 1016, TO_DATE('2018-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
SELECT * FROM dual;

INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1011, 3, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1015, 2, 'A', 'Behaviour', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1005, 5, 'A', 'Age', 'Management');

INSERT ALL
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1004, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1003, TO_DATE('2019-04-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1006, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1012, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1002, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1014, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1000, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1010, TO_DATE('2019-03-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1009, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1011, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1008, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1015, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1012, 1016, TO_DATE('2019-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
SELECT * FROM dual;

INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1003, 4, 'A', 'Behaviour', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1002, 4, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1014, 3, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1008, 5, 'A', 'Age', 'Management');

INSERT ALL
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1007, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1001, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1004, TO_DATE('2020-04-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1003, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1006, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1012, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1002, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1014, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1010, TO_DATE('2020-03-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1009, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1011, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1015, TO_DATE('2020-01-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
    INTO REGISTERED (COURSE_ID,STUDENT_ID,REG_DATE) VALUES (1013, 1016, TO_DATE('2020-03-04 00:00:00','yyyy/mm/dd hh24:mi:ss'))
SELECT * FROM dual;
    
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1003, 4, 'A', 'Behaviour', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1002, 4, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1009, 4, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1010, 5, 'A', 'Age', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1003, 5, 'A', 'Improvement', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1012, 5, 'A', 'Intelligence', 'Management');
INSERT INTO CLASS_LOG (STUDENT_ID,CLASS_NO,SECTION,REASON,APPROVED) VALUES (1016, 4, 'B', 'Improvement', 'Management');

UPDATE CLASS
SET GROUPING = 'YES'
WHERE CLASS_ID = 1079;

--======================================[ INSERTING INTO ACCOMPANY]=====================================
INSERT ALL
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1010, 1050, 1005, 1040, TO_DATE('2018-04-23 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'ILL', 0)
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1011, 1064, 1003, 1037, TO_DATE('2020-08-18 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'Out of City', 0)
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1012, 1069, 1008, 1037, TO_DATE('2018-10-06 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'Work Meeting', 0)
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1013, 1077, 1001, 1031, TO_DATE('2020-09-30 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'Busy', 1)
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1010, 1055, 1009, 1031, TO_DATE('2018-09-27 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'ILL', 0)
    INTO ACCOMPANY (COURSE_ID,CLASS_ID,STUDENT_ID,GUARDIAN_ID,A_DATE,REASON,PREGNANT) VALUES (1013, 1075, 1006, 1041, TO_DATE('2020-05-19 00:00:00','yyyy/mm/dd hh24:mi:ss'), 'Out of City', 1)
SELECT * FROM DUAL;