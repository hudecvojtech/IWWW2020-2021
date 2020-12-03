<?php


class Event
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function insertEvent($name, $start, $end, $calendarId, $categoryId) {
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
            $arr[$i]["CATEGORY_id_category"] = $row["CATEGORY_id_category"];
            $i++;
        }

        return $arr;
    }

    public function deleteEvent($eventId)
    {
        $query = "DELETE FROM event_calendar WHERE id_event_calendar = '$eventId'";
        $this->conn->query($query);
    }
}