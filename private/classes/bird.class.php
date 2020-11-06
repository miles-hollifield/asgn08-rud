<?php

 class Bird {

    // -- Start of Active Record Code -- //
    
    static protected $database;
    static protected $db_columns = ['id', 'common_name', 'habitat', 'food', 'conservation_id', 'backyard_tips'];
    public $errors = [];
   
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
    public $conservation_id=1;

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

    protected function validate() {
      $this->errors = [];
      
      if(is_blank($this->common_name)) {
        $this->errors[] = "Name cannot be blank.";
      }
      if(is_blank($this->habitat)) {
        $this->errors[] = "Habitat cannot be blank.";
      }
      if(is_blank($this->food)) {
        $this->errors[] = "Food cannot be blank.";
      }
      if(is_blank($this->conservation_id)) {
        $this->errors[] = "Conservation level cannot be blank.";
      }
      if(is_blank($this->backyard_tips)) {
        $this->errors[] = "Backyard tips cannot be blank.";
      }
      return $this->errors;
    } 
   
    protected function create() {
        $this->validate();
        if(!empty($this->errors)) { return false; }
        $attributes = $this->attributes();
        $sql = "INSERT INTO birds (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
//        $sql .= ':common_name, :habitat, :food, :conservation_id, :backyard_tips';
        $sql .= "');";

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
   
    protected function update() {
      $this->validate();
      if(!empty($this->errors)) { return false; }
      $attributes = $this->attributes();
//      $attribute_pairs = [];
//      foreach($attributes as $key => $value) {
//        $attribute_pairs[] = "{$key}='{$value}'";
//      }
      $sql = "UPDATE birds SET ";
      $sql .= "common_name = :common_name, ";
      $sql .= "habitat = :habitat, ";
      $sql .= "food = :food, ";
      $sql .= "conservation_id = :conservation_id, ";
      $sql .= "backyard_tips = :backyard_tips ";
      $sql .= "WHERE id='" . $this->id . "' ";
      $sql .= "LIMIT 1";
      
      $stmt = self::$database->prepare($sql);
      
      $stmt->bindValue(':common_name', $this->common_name );
      $stmt->bindValue(':habitat', $this->habitat );
      $stmt->bindValue(':food', $this->food );
      $stmt->bindValue(':conservation_id', $this->conservation_id );
      $stmt->bindValue('backyard_tips', $this->backyard_tips );
      
      $result = $stmt->execute();
 
      return $result;
      
    }
   
    public function save() {
      // A new record will not have an ID yet
      if(isset($this->id)) {
        return $this->update();
      } else {
        return $this->create();
      }
    }

    public function merge_attributes($args=[]) {
      foreach($args as $key => $value) {
        if(property_exists($this, $key) && !is_null($value)) {
          $this->$key = $value;
        }
      }
    }
   
    // Properties which have database columns, excluding ID
    public function attributes() {
      $attributes = [];
      foreach(self::$db_columns as $column) {
        if ($column == 'id') { continue; }
        $attributes[$column] = $this->$column;
      }
      return $attributes;
    }
   
    protected function sanitized_attributes() {
      $sanitized = [];
      foreach($this->attributes() as $key => $value) {
        $sanitized[$key] = self::$database->escape_string($value);
      }
      return $sanitized;
    }
   
    public function delete() {
      $sql = "DELETE FROM birds ";
      $sql .= "WHERE id='" . $this->id . "' ";
      $sql .= "LIMIT 1";
      $stmt = self::$database->prepare($sql);
      $result = $stmt->execute();
      return $result;
      
      // After deleting, the instance of the object will still exist, even though the database record does not.
      // This can be userful, as in:
      //  echo $user->first_name . " was deleted.";
      // but, for example, we can't call $user->update() after calling $user->delete().
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