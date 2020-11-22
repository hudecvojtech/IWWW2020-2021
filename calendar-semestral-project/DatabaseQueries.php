<?php


class DatabaseQueries
{
    public static function userFindByEmail($email) {
        return "SELECT * FROM users WHERE email = '$email'";
    }

    public static function userInsert($email, $password, $firstname, $lastname, $roleId, $avatarId) {
        return "INSERT INTO users(email, password, firstname, lastname, ROLE_id_role, AVATAR_id_avatar)
VALUES ('$email', '$password', '$firstname', '$lastname', $roleId, $avatarId)";
    }

    public static function roleNameById($id) {
        return "SELECT name FROM role WHERE id_role = '$id'";
    }

    public static function avatarPathById($id) {
        return "SELECT path FROM avatar WHERE id_avatar = '$id'";
    }

    public static function calendarIdByUserId($id) {
        return "SELECT CALENDAR_id_calendar FROM users_calendars WHERE USER_id_user = '$id'";
    }

    public static function calendarNameById($id) {
        return "SELECT name FROM calendar WHERE id_calendar = '$id'";
    }

    public static function insertCalendar($name, $validUntil) {
        return "INSERT INTO calendar(name, valid_until) VALUES('$name', '$validUntil')";
    }

    public static function calendarIdByName($name) {
        return "SELECT id_calendar FROM calendar WHERE name = '$name'";
    }

    public static function insertUserIdCalendarId($userId, $calendarId) {
        return "INSERT INTO users_calendars(USER_id_user, CALENDAR_id_calendar) VALUES($userId, $calendarId)";
    }

    public static function insertEvent($name, $start, $end, $calendarId) {
        return "INSERT INTO eventtable(name, start, end, CALENDAR_id_calendar) VALUES('$name', '$start', '$end', '$calendarId')";
    }

    public static function selectEvents($calendarId) {
        return "SELECT * FROM eventtable WHERE CALENDAR_id_calendar = '$calendarId'";
    }

    public static function deleteEvent($eventId)
    {
        return "DELETE FROM eventtable WHERE id_event = '$eventId'";
    }

    public static function deleteCalendar($calendarId)
    {
        return "DELETE FROM users_calendars WHERE CALENDAR_id_calendar = '$calendarId'; DELETE FROM eventtable WHERE CALENDAR_id_calendar = '$calendarId';
 DELETE FROM calendar WHERE id_calendar = '$calendarId'";
    }

    public static function updateAvatar($avatarId, $userId) {
        return "UPDATE users SET AVATAR_id_avatar = '$avatarId' WHERE id_user = '$userId'";
    }

    public static function updateFirstName($firstname, $userId) {
        return "UPDATE users SET firstname = '$firstname' WHERE id_user = '$userId'";
    }

    public static function updateLastName($lastname, $userId) {
        return "UPDATE users SET lastname = '$lastname' WHERE id_user = '$userId'";
    }

    public static function updateEmail($email, $userId) {
        return "UPDATE users SET email = '$email' WHERE id_user = '$userId'";
    }

    public static function updatePassword($password, $userId) {
        return "UPDATE users SET password = '$password' WHERE id_user = '$userId'";
    }

    public static function insertAvatar($path) {
        return "INSERT INTO avatar(path) VALUES ('$path')";
    }

    public static function selectAvatarIdByPath($path) {
        return "SELECT id_avatar FROM avatar WHERE path = '$path'";
    }

    public static function deleteAvatar($id) {
        return "DELETE FROM avatar WHERE id_avatar = '$id'";
    }
}