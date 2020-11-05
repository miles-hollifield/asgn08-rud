<?php

 class Bird {

    // -- Start of Active Record Code -- //
    
    static protected $database;

    static public function set_database($database) {
        self::$database = $database;
    }

    static public function find_by_sql($sql) {
        $result = self::$database->query($sql);
        if(!$result) {
            exit("<p>Database query failed</p>");
        }

        // Turn results into objects
        $object_array = [];
        while ($record = $result->fetch(PDO::FETCH_ASSOC)) {
            $object_array[] = self::instantiate($record);
          }
        //  $result->free();
        return $object_array;
    }
    
    static public function find_by_id($id) {
        $sql = "SELECT * FROM birds ";
        $sql .= "WHERE id=" . self::$database->quote($id);
        $object_array = self::find_by_sql($sql);
        if(!empty($object_array)) {
            return array_shift($object_array);
        }   else    {
            return false;
        }
    }
    static public function find_all() {
        $sql = "SELECT * FROM birds";
        return self::find_by_sql($sql);
    }

    static public function instantiate($record) {
        $object = new self;
        foreach($record as $property => $value) {
            if(property_exists($object, $property)) {
                $object->$property = $value;
            }
        }
        return $object;
    }



    // -- End of Active Record Code -- //

    public $id;
    public $common_name;
    public $habitat;
    public $food;
    public $nest_palcement;
    public $behavior;
    public $backyard_tips;
    protected $conservation_id=1;

    public const CONSERVATION_OPTIONS = [ 
        1 => "Low concern",
        2 => "Medium concern",
        3 => "High concern",
        4 => "Extreme concern"
    ];

    public function __construct($args=[]) {
        $this->common_name = $args['common_name'] ?? '';
        $this->habitat = $args['habitat'] ?? '';
        $this->food = $args['food'] ?? '';
        $this->nest_palcement = $args['nest_palcement'] ?? '';
        $this->behavior = $args['behavior'] ?? '';
        $this->backyard_tips = $args['backyard_tips'] ?? '';
        $this->conservation_id = $args['conservation_id'] ?? '';

    }
    
    public function create_not_bound() {
        $sql = "INSERT INTO birds (common_name, habitat, food, conservation_id, backyard_tips)";
        $sql .= " VALUES (";
        $sql .= "'" . $this->common_name . "', ";
        $sql .= "'" . $this->habitat . "', ";
        $sql .= "'" . $this->food . "', ";
        $sql .= "'" . $this->conservation_id . "', ";
        $sql .= "'" . $this->backyard_tips . "'";
        $sql .= ")";

        $result = self::$database->exec($sql);

        if( $result ) {
            $this->id = self::$database->lastInsertID();
        } else  echo "Insert query did not run";
        
        return $result;
        
    }

    public function create() {
        $sql = "INSERT INTO birds (common_name, habitat, food, conservation_id, backyard_tips)";
        $sql .= " VALUES (";
        $sql .= ':common_name, :habitat, :food, :conservation_id, :backyard_tips';
        $sql .= ");";

        $stmt = self::$database->prepare($sql);
        
        $stmt->bindValue(':common_name', $this->common_name );
        $stmt->bindValue(':habitat', $this->habitat );
        $stmt->bindValue(':food', $this->food );
        $stmt->bindValue(':conservation_id', $this->conservation_id );
        $stmt->bindValue('backyard_tips', $this->backyard_tips );
        
        //$result = self::$database->exec($sql);
        $result = $stmt->execute();

        if( $result ) {
            $this->id = self::$database->lastInsertID();
        } else  echo "Insert query did not run";
        
        return $result;
        
    }

    public function conservation() {
        // echo self::CONSERVATION_OPTIONS[$this->conservation_id];
        if( $this->conservation_id > 0 ) {
            return self::CONSERVATION_OPTIONS[$this->conservation_id];
        } else {
            return "Unknown";
        }
    }


}