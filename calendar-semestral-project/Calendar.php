<?php

class Calendar
{
    private $conn;

    public function __construct(\PDO $pdo)
    {
        $this->conn = $pdo;
    }

    public function getCalendarsByUserId($userId)
    {
        $arr[][] = NULL;
        $i = 0;
        $query = "SELECT CALENDAR_id_calendar FROM users_calendars WHERE USER_id_user = '$userId'";
        $stmt = $this->conn->query($query);
        while ($row = $stmt->fetch()) {
            $arr[$i]["id"] = $row[0];
            $query = "SELECT name FROM calendar WHERE id_calendar = '$row[0]'";
            $stmtCal = $this->conn->query($query);
            while ($cal = $stmtCal->fetch()) {
                $arr[$i]["name"] = $cal[0];
            }
            $i++;
        }
        return $arr;
    }

    public function getCalendars()
    {
        $arr[][] = NULL;
        $i = 0;
        $query = "SELECT * FROM calendar";
        $stmt = $this->conn->query($query);
        while ($row = $stmt->fetch()) {
            $arr[$i++] = $row;
        }

        return $arr;
    }

    public function getCalendarId($name)
    {
        $query = "SELECT id_calendar FROM calendar WHERE name = '$name'";
        $stmt = $this->conn->query($query);
        $count = $stmt->rowCount();
        if ($count == 0) return 0;

        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public function getCalendarName($id)
    {
        $query = "SELECT name FROM calendar WHERE id_calendar = '$id'";
        $stmt = $this->conn->query($query);
        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public function insertUserIdCalendarId($userId, $calendarId)
    {
        $query = "INSERT INTO users_calendars(USER_id_user, CALENDAR_id_calendar) VALUES($userId, $calendarId)";
        $this->conn->query($query);
    }

    public function insertCalendar($name, $validUntil)
    {
        $query = "INSERT INTO calendar(name, valid_until) VALUES('$name', '$validUntil')";
        $statement = $this->conn->prepare($query);
        if (!$statement->execute()) {
            echo $statement->errorInfo();
        } else {
            $this->insertUserIdCalendarId($_SESSION["id"], $this->getCalendarId($name));
        }
    }

    public function deleteCalendar($calendarId)
    {
        $query = "DELETE FROM users_calendars WHERE CALENDAR_id_calendar = '$calendarId'; 
DELETE FROM event_calendar WHERE CALENDAR_id_calendar = '$calendarId';
 DELETE FROM calendar WHERE id_calendar = '$calendarId'";
        $this->conn->query($query);
    }
}