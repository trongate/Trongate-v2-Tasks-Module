<?php
/**
 * Tasks Model - Handles data operations for task records
 * 
 * Demonstrates proper data conversion patterns, especially for checkboxes,
 * and separation of concerns between database operations and presentation logic.
 */
class Tasks_model extends Model {
    
    /**
     * Fetch paginated task records from database
     * 
     * Retrieves tasks with proper limit and offset for pagination.
     * This is the primary method for listing tasks in manage view.
     * 
     * @param int $limit Maximum number of records to return
     * @param int $offset Number of records to skip (for pagination)
     * @return array Array of task record objects
     */
    public function fetch_records(int $limit, int $offset): array {
        return $this->db->get('id', 'tasks', $limit, $offset);
    }
    
    /**
     * Get form-ready data based on current context
     * 
     * Determines whether to return existing record data (for editing)
     * or POST data/default values (for new forms or validation errors).
     * This is the main method called by controller's create() method.
     * 
     * @param int $update_id Record ID to edit, or 0 for new records
     * @return array Form data ready for view display
     * @example get_form_data(5) returns task #5 data for editing
     * @example get_form_data(0) returns POST data or defaults for new task
     */
    public function get_form_data(int $update_id = 0): array {
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
     * @return array Record data with checkbox converted to boolean
     * @throws No explicit throws, but returns empty array if record not found
     */
    public function get_data_for_edit(int $update_id): array {
        $record = $this->db->get_where($update_id, 'tasks');
        
        if (empty($record)) {
            return [];
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
     * @note Checkbox conversion: '1' → true, '' → false
     */
    private function get_data_from_post_or_defaults(): array {
        $data = [
            'task_title' => post('task_title', true) ?? '',
            'task_description' => post('task_description', true) ?? '',
            'complete' => (bool) post('complete', true) // Convert to boolean
        ];
        
        return $data;
    }
    
    /**
     * Prepare POST data for database storage
     * 
     * Converts form submission data to database-ready format.
     * Key conversion: checkbox boolean → integer (0/1) for database.
     * This is the counterpart to get_data_from_post_or_defaults().
     * 
     * @return array Database-ready data with proper types
     * @note Checkbox conversion: true → 1, false → 0
     */
    public function get_post_data_for_database(): array {
        return [
            'task_title' => post('task_title', true),
            'task_description' => trim(post('task_description')),
            'complete' => (int) (bool) post('complete', true) // Convert to 0/1
        ];
    }
    
    /**
     * Prepare raw database data for display in views
     * 
     * Adds formatted versions of fields while preserving raw data.
     * This is where you add display-friendly versions of data.
     * 
     * @param array $data Raw data from database
     * @return array Enhanced data with formatted fields
     * @example Converts complete=1 to complete_formatted='Complete'
     */
    public function prepare_for_display(array $data): array {
        // Format completion status for display
        if (isset($data['complete'])) {
            $data['complete_formatted'] = ($data['complete'] == 1) 
                ? 'Complete' 
                : 'Incomplete';
        }
        
        // Future: Add more formatted fields as needed
        // if (isset($data['date_created'])) {
        //     $data['date_created_formatted'] = date('d/m/Y H:i:s', $data['date_created']);
        // }
        
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
}