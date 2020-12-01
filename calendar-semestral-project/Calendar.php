<?php

class Calendar
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function getCalendars($userId) {
        $arr[][] = NULL;
        $i = 0;
        $query = "SELECT CALENDAR_id_calendar FROM users_calendars WHERE USER_id_user = '$userId'";
        $stmt = $this->conn->query($query);
        while($row = $stmt->fetch()) {
            $arr[$i]["id"] = $row[0];
            $query = "SELECT name FROM calendar WHERE id_calendar = '$row[0]'";
            $stmtCal = $this->conn->query($query);
            while($cal = $stmtCal->fetch()) {
                $arr[$i]["name"] = $cal[0];
            }
            $i++;
        }
        return $arr;
    }

    public function calendarIdByName($name) {
        $query = "SELECT id_calendar FROM calendar WHERE name = '$name'";
        $stmt = $this->conn->query($query);
        $count = $stmt->rowCount();
        if($count == 0) return 0;

        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public function calendarNameById($id) {
        $query = "SELECT name FROM calendar WHERE id_calendar = '$id'";
        $stmt = $this->conn->query($query);
        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public function insertUserIdCalendarId($userId, $calendarId) {
        $query = "INSERT INTO users_calendars(USER_id_user, CALENDAR_id_calendar) VALUES($userId, $calendarId)";
        $this->conn->query($query);
    }

    public function calendarInsert($name, $validUntil) {
        $query = "INSERT INTO calendar(name, valid_until) VALUES('$name', '$validUntil')";
        $statement = $this->conn->prepare($query);
        if(!$statement->execute()) {
            echo $statement->errorInfo();
        } else {
            $this->insertUserIdCalendarId($_SESSION["id"], $this->calendarIdByName($name));
        }
    }

    public function eventInsert($name, $start, $end, $calendarId, $categoryId) {
        $query = "INSERT INTO event_calendar(name, start, end, CALENDAR_id_calendar, CATEGORY_id_category) 
VALUES('$name', '$start', '$end', '$calendarId', '$categoryId')";
        $this->conn->query($query);
    }

    public function selectEvents($calendarId) {
        $arr[][] = NULL;
        $i = 0;
        $query = "SELECT * FROM event_calendar WHERE CALENDAR_id_calendar = '$calendarId'";
        $stmt = $this->conn->query($query);

        while($row = $stmt->fetch()) {
            $arr[$i]["name"] = $row["name"];
            $arr[$i]["start"] = $row["start"];
            $arr[$i]["end"] = $row["end"];
            $arr[$i]["id"] = $row["id_event_calendar"];
            $i++;
        }

        return $arr;
    }

    public function deleteEvent($eventId)
    {
        $query = "DELETE FROM event_calendar WHERE id_event = '$eventId'";
        $this->conn->query($query);
    }

    public function deleteCalendar($calendarId)
    {
        $query = "DELETE FROM users_calendars WHERE CALENDAR_id_calendar = '$calendarId'; 
DELETE FROM event_calendar WHERE CALENDAR_id_calendar = '$calendarId';
 DELETE FROM calendar WHERE id_calendar = '$calendarId'";
        $this->conn->query($query);
    }
}