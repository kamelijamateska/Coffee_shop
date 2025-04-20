<?php
// Дефиниција на класата TableBooking која се користи за управување со резервациите на маси
class TableBooking {
    // Приватна променлива за чување на врската со базата на податоци
    private $conn;
    
    // Конструкторот ја иницијализира врската со базата на податоци
    public function __construct($db) {
        $this->conn = $db; // Зачувување на врската во променливата $conn
    }
    
    // Метода bookTable за правење нова резервација на маса
    public function bookTable($userId, $date, $time, $numGuests) {
        // SQL барање за внесување на нова резервација во табелата "table_bookings"
        $query = "INSERT INTO table_bookings (user_id, date, time, num_guests) 
                  VALUES (:userId, :date, :time, :numGuests)";
        
        try {
            // Подготовка на SQL барањето
            $stmt = $this->conn->prepare($query);
            // Поврзување на параметрите со нивните вредности
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":date", $date);
            $stmt->bindParam(":time", $time);
            $stmt->bindParam(":numGuests", $numGuests);
            
            // Извршување на барањето; враќа true ако е успешно, во спротивно false
            return $stmt->execute();
        } catch(PDOException $e) {
            // Во случај на грешка, враќа false
            return false;
        }
    }
    
    // Метод getBookings за добивање на резервациите на корисник или сите резервации
    public function getBookings($userId = null) {
        // SQL барање за добивање на сите резервации со информации за корисникот
        $query = "SELECT b.*, u.username 
                  FROM table_bookings b 
                  JOIN users u ON b.user_id = u.id";
        
        // Ако е даден userId, додава услов WHERE за да се филтрираат резервациите по корисник
        if($userId) {
            $query .= " WHERE b.user_id = :userId";
        }
        
        try {
            // Подготовка на SQL барањето
            $stmt = $this->conn->prepare($query);
            // Ако има userId, поврзување на параметарот
            if($userId) {
                $stmt->bindParam(":userId", $userId);
            }
            // Извршување на барањето
            $stmt->execute();
            // Враќање на сите резултати како асоцијативна низа
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Во случај на грешка, враќа празна низа
            return [];
        }
    }
}
?>

