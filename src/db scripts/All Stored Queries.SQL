----- QUERY 1: A list of all students -----
SELECT * FROM STUDENT;

----- QUERY 2: A list of all mothers and their spouses -----
SELECT UNIQUE S.MOTHER_ID, S.FATHER_ID, G.NAME AS MOTHER_NAME, GG.NAME AS FATHER_NAME
FROM STUDENT S
JOIN GUARDIAN G ON S.MOTHER_ID = G.ID 
JOIN GUARDIAN GG ON S.FATHER_ID = GG.ID
ORDER BY S.MOTHER_ID, S.FATHER_ID;

----- QUERY 3: A list of all guardians, grouped by relation to students (show students as well) -----
SELECT DISTINCT S.ROLL_NO, S.NAME AS STUDENT_NAME, G.ID AS GUARDIAN_ID,G.NAME AS GUARDIAN_NAME, G.RELATION
FROM STUDENT S INNER JOIN GUARDIAN G 
ON S.GUARDIAN_ID = G.ID
ORDER BY S.ROLL_NO;

----- QUERY 4: A list of parents and their children -----
SELECT S.NAME AS STUDENT_NAME, G.NAME AS FATHER_NAME, GG.NAME AS MOTHER_NAME
FROM STUDENT S
JOIN GUARDIAN G ON S.MOTHER_ID = G.ID
JOIN GUARDIAN GG ON S.FATHER_ID = GG.ID;

----- QUERY 5: A list of all students with siblings taking classes, grouped by class -----
CREATE VIEW PARENT AS SELECT DISTINCT FATHER_ID, MOTHER_ID
FROM STUDENT;
CREATE VIEW SIBLINGS AS SELECT S.ROLL_NO, S.MOTHER_ID, S.FATHER_ID 
FROM STUDENT S, PARENT P 
WHERE P.FATHER_ID = S.FATHER_ID AND P.MOTHER_ID = S.MOTHER_ID;

SELECT DISTINCT R.STUDENT_ID, C.CLASS_NO, SIB.ROLL_NO, S.NAME, S.MOTHER_ID, S.FATHER_ID FROM REGISTERED R,CLASS C,SIBLINGS SIB, STUDENT S
WHERE SIB.ROLL_NO = S.ROLL_NO AND  SIB.FATHER_ID = S.FATHER_ID AND SIB.MOTHER_ID = S.MOTHER_ID
AND S.ID = R.STUDENT_ID AND C.CLASS_ID = R.CLASS_ID AND C.COURSE_ID = 1013 
ORDER BY S.MOTHER_ID, C.CLASS_NO;

----- QUERY 6: A list of all students who have been assigned to a new class in the given time span -----
----- IGNORING NEW ADMISSIONS -----
SELECT S.ROLL_NO AS STUDENT_ROLL_NO, C.CLASS_NO , R.REG_DATE FROM STUDENT S, CLASS C , REGISTERED R WHERE EXTRACT(YEAR FROM R.REG_DATE) >= '2018'
AND R.CLASS_ID = C.CLASS_ID AND R.STUDENT_ID = S.ID AND EXTRACT(YEAR FROM S.DATE_ADMITTED) < '2018';
----- INCLUDING ALL ADMISSIONS -----
SELECT S.ROLL_NO AS STUDENT_ROLL_NO, C.CLASS_NO , R.REG_DATE FROM STUDENT S, CLASS C , REGISTERED R WHERE EXTRACT(YEAR FROM R.REG_DATE) >= '2018'
AND R.CLASS_ID = C.CLASS_ID AND R.STUDENT_ID = S.ID AND EXTRACT(YEAR FROM S.DATE_ADMITTED) >= '2018';

----- QUERY 7: A list of all new students in given time span grouped by class -----
SELECT COUNT(*) AS TOTAL_NEW_STUDENTS, C.CLASS_NO AS CLASS
FROM STUDENT S, REGISTERED R, CLASS C
WHERE EXTRACT(YEAR FROM S.DATE_ADMITTED) >= '2018'
AND S.ID = R.STUDENT_ID AND R.CLASS_ID = C.CLASS_ID
GROUP BY C.CLASS_NO
ORDER BY C.CLASS_NO;

----- QUERY 8: A list of all new parents in given time span (include children info) -----
SELECT DISTINCT S.ROLL_NO, S.NAME AS CHILD_NAME,
 	S.DATE_ADMITTED, G.ID AS PARENT_ID,
	G.NAME AS PARENT_NAME, G.CONTACT, G.CNIC,
	G.EMAIL, G.ADDRESS,
	G.RELATION, G.GENDER, G.IS_STAFF
FROM GUARDIAN G, STUDENT S
WHERE (G.ID = S.FATHER_ID OR G.ID = S.MOTHER_ID)
AND EXTRACT(YEAR FROM S.DATE_ADMITTED) >= '2018'
ORDER BY S.DATE_ADMITTED;

----- QUERY 9: A list of all parents who are early introducers -----
SELECT DISTINCT G.ID, G.CNIC, G.NAME, 
	G.GENDER, G.CONTACT, G.EMAIL,
	G.ADDRESS, G.RELATION, G.IS_STAFF
FROM GUARDIAN G, STUDENT S
WHERE S.AGE >= 3 AND S.AGE <= 4 
AND (G.ID = S.FATHER_ID OR G.ID = S.MOTHER_ID);

----- QUERY 10: Class change history of given student -----
SELECT * FROM CLASS_LOG WHERE STUDENT_ID = 1005;