<?php


class Event
{
    private $conn;

    public function __construct(\PDO $pdo) {
        $this->conn = $pdo;
    }

    public function insertCalendarsEvents($calendarId, $eventId, $owner) {
        $query = "INSERT INTO calendars_events(CALENDAR_id_calendar, EVENT_CALENDAR_id_event_calendar, owner) 
VALUES('$calendarId', '$eventId', '$owner')";
        $this->conn->query($query);
    }

    public function getAccess($calendarId, $eventId) {
        $query = "SELECT owner FROM calendars_events WHERE CALENDAR_id_calendar = '$calendarId' AND
 EVENT_CALENDAR_id_event_calendar = '$eventId'";
        return $this->conn->query($query)->fetch()[0];
    }

    public function insertEvent($name, $start, $end, $calendarId, $categoryId) {
        $query = "INSERT INTO event_calendar(name, start, end, CATEGORY_id_category) 
VALUES('$name', '$start', '$end', '$categoryId')";

        $this->conn->query($query);
        $query = "SELECT id_event_calendar FROM event_calendar ORDER BY id_event_calendar DESC LIMIT 1";
        $result = $this->conn->query($query);
        $eventId = $result->fetch()[0];
        $this->insertCalendarsEvents($calendarId, $eventId, "owner");
    }

    public function updateEvent($eventId, $name, $start, $end, $categoryId) {
        $query = "UPDATE event_calendar SET name = '$name', start = '$start', end = '$end', CATEGORY_id_category = '$categoryId' WHERE id_event_calendar = '$eventId'";
        echo $query;
        $this->conn->query($query);
    }

    public function selectEvents($calendarId) {
        $arr[][] = NULL;
        $i = 0;
        $query = "SELECT * FROM calendars_events WHERE CALENDAR_id_calendar = '$calendarId'";
        $stmt = $this->conn->query($query);

        while($row = $stmt->fetch()) {
            $eventId = $row["EVENT_CALENDAR_id_event_calendar"];
            $queryEvent = "SELECT * FROM event_calendar WHERE id_event_calendar = '$eventId'";
            $eventResult = $this->conn->query($queryEvent);
            $eventResult = $eventResult->fetch();
            $arr[$i]["name"] = $eventResult["name"];
            $arr[$i]["start"] = $eventResult["start"];
            $arr[$i]["end"] = $eventResult["end"];
            $arr[$i]["id"] = $eventResult["id_event_calendar"];
            $arr[$i]["CATEGORY_id_category"] = $eventResult["CATEGORY_id_category"];
            $i++;
        }

        return $arr;
    }

    public function deleteEvent($eventId)
    {
        $query = "DELETE FROM calendars_events WHERE EVENT_CALENDAR_id_event_calendar = '$eventId'";
        $this->conn->query($query);
        $query = "DELETE FROM event_calendar WHERE id_event_calendar = '$eventId'";
        $this->conn->query($query);
    }

    public function getEvent($eventId) {
        $query = "SELECT * FROM event_calendar WHERE id_event_calendar = '$eventId'";
        return $this->conn->query($query)->fetch();
    }
}