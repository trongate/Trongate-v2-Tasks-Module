<?php
/**
 * Tasks Model - Handles data operations for task records
 * 
 * Demonstrates proper data conversion patterns, especially for checkboxes,
 * and separation of concerns between database operations and presentation logic.
 */
class Tasks_model extends Model {
    
    private string $table_name = 'tasks';

    /**
     * Find a single record based on a column value.
     *
     * @param string $column The column name to match
     * @param mixed $value The value to match
     * @return object|false Returns record object if found, false otherwise
     */
    private function find_one(string $column, $value): object|false {
        $sql = 'SELECT * FROM '.$this->table_name.' WHERE '.$column.' = :'.$column.' LIMIT 1';
        $params = [$column => $value];
        $rows = $this->db->query_bind($sql, $params, 'object');
        return !empty($rows) ? $rows[0] : false;
    }

    /**
     * Find a task record by ID.
     *
     * @param int $task_id The task identifier to look up
     * @return object|false Returns task object if found, false otherwise
     */
    public function find_by_id(int $task_id): object|false {
        return $this->find_one('id', $task_id);
    }

    /**
     * Fetch paginated records.
     *
     * @param int $limit Records per page
     * @param int $offset Records to skip
     * @return array<object>
     */
    public function fetch_records(int $limit, int $offset): array {
        $rows_to_return = (int) $limit;
        $rows_to_skip = (int) $offset;
        
        $sql = 'SELECT * FROM '.$this->table_name.' ORDER BY id LIMIT '.$rows_to_return.' OFFSET '.$rows_to_skip;
        return $this->db->query($sql, 'object');
    }
    
    /**
     * Get form-ready data based on current context
     * 
     * Determines whether to return existing record data (for editing)
     * or POST data/default values (for new forms or validation errors).
     * This is the main method called by controller's create() method.
     * 
     * @param int $update_id Record ID to edit, or 0 for new records
     * @return array|false Form data ready for view display, or false if not found
     */
    public function get_form_data(int $update_id = 0): array|false {
        if ($update_id > 0 && REQUEST_TYPE === 'GET') {
            return $this->get_data_for_edit($update_id);
        } else {
            return $this->get_data_from_post_or_defaults();
        }
    }

    /**
     * Get existing record data for editing
     * 
     * Fetches a single record from database and prepares it for form display.
     * Key conversion: database integer (0/1) → boolean for checkbox.
     * 
     * @param int $update_id The record ID to fetch
     * @return array|false Record data with checkbox converted to boolean, or false if not found
     */
    public function get_data_for_edit(int $update_id): array|false {
        $record = $this->find_by_id($update_id);
        
        if ($record === false) {
            return false;
        }
        
        $data = (array) $record;
        
        // Convert database 0/1 to boolean for checkbox display
        if (isset($data['complete'])) {
            $data['complete'] = (bool) $data['complete'];
        }
        
        return $data;
    }
    
    /**
     * Get form data from POST or use defaults
     * 
     * Used for new forms or when redisplaying form after validation errors.
     * Converts checkbox POST data to boolean for view display.
     * 
     * @return array Form data with proper types for view
     */
    private function get_data_from_post_or_defaults(): array {
        $data = [
            'task_title' => post('task_title', true) ?? '',
            'task_description' => post('task_description', true) ?? '',
            'complete' => (bool) post('complete', true)
        ];
        
        return $data;
    }
    
    /**
     * Prepare POST data for database storage
     * 
     * Converts form submission data to database-ready format.
     * Key conversion: checkbox boolean → integer (0/1) for database.
     * 
     * @return array Database-ready data with proper types
     */
    public function get_post_data_for_database(): array {
        return [
            'task_title' => post('task_title', true),
            'task_description' => trim(post('task_description', true)),
            'complete' => (int) (bool) post('complete', true)
        ];
    }
    
    /**
     * Count all records in the database table.
     *
     * @return int  Total number of records.
     */
    public function count_all(): int {
        $num_rows = $this->db->count($this->table_name);
        return $num_rows;
    }

    /**
     * Prepare raw database data for display in views
     * 
     * Adds formatted versions of fields while preserving raw data.
     * This is where you add display-friendly versions of data.
     * 
     * @param array $data Raw data from database
     * @return array Enhanced data with formatted fields
     */
    public function prepare_for_display(array $data): array {
        // Format completion status for display
        if (isset($data['complete'])) {
            $data['complete_formatted'] = ($data['complete'] == 1) 
                ? 'Complete' 
                : 'Incomplete';
        }
        
        return $data;
    }
    
    /**
     * Prepare multiple records for display in list views
     * 
     * Processes an array of records through prepare_for_display().
     * Maintains object structure for consistency with Trongate patterns.
     * 
     * @param array $rows Array of record objects from database
     * @return array Array of objects with formatted display fields
     */
    public function prepare_records_for_display(array $rows): array {
        $prepared = [];
        foreach ($rows as $row) {
            $row_array = (array) $row;
            $prepared[] = (object) $this->prepare_for_display($row_array);
        }
        return $prepared;
    }

    /**
     * Create a new task record.
     * 
     * @param array $data Task data including title, description, complete status
     * @return int Returns the ID of the newly created record
     */
    public function create_new_record(array $data): int {
        return $this->db->insert($data, $this->table_name);
    }

    /**
     * Update an existing task record.
     * 
     * @param int $update_id The ID of the record to update
     * @param array $data The data to update
     * @return void
     */
    public function update_record(int $update_id, array $data): void {
        $this->db->update($update_id, $data, $this->table_name);
    }

    /**
     * Delete a task record.
     * 
     * @param int $update_id The ID of the record to delete
     * @return void
     */
    public function delete_record(int $update_id): void {
        $this->db->delete($update_id, $this->table_name);
    }
}