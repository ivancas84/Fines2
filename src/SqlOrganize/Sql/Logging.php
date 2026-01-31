<?php

namespace SqlOrganize\Sql;

/**
 * Registro de errores
 */
class Logging
{
    const LEVEL_SUCCESS = 0;
    const LEVEL_INFO = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_ERROR = 3;

    /**
     * Dictionary to store logs grouped by key.
     * @example
     * [
     *   "asignatura" => [
     *       ["level" => LEVEL_ERROR, "message" => "No puede estar vacío", "type" => "required"]
     *   ],
     *   "plan" => [
     *       ["level" => LEVEL_WARNING, "message" => "No tiene cargas horarias asociadas", "type" => "user"]
     *   ],
     *   "numero" => [
     *       ["level" => LEVEL_ERROR, "message" => "No es unico", "type" => "not_unique"],
     *       ["level" => LEVEL_WARNING, "message" => "Esta fuera del rango permitido", "type" => "out_of_range"]
     *   ]
     * ]
     */
    private array $logs = [];

    /**
     * Get logs for a specific key.
     */
    public function getLogsByKey(string $key): ?array
    {
        return $this->logs[$key] ?? null;
    }

    /**
     * Clears logs for a specific key.
     */
    public function clearByKey(string $key): void
    {
        unset($this->logs[$key]);
    }

    /**
     * Clears all logs.
     */
    public function clear(): void
    {
        $this->logs = [];
    }

    /**
     * Adds a new log entry.
     */
    public function addLog(string $key, string $message, ?string $type = null, int $level = self::LEVEL_INFO): void
    {
        if (!isset($this->logs[$key])) {
            $this->logs[$key] = [];
        }

        $this->logs[$key][] = [
            'level' => $level,
            'message' => $message,
            'type' => $type
        ];

        // Sorting logs by level
        usort($this->logs[$key], function($a, $b) {
            return $a['level'] <=> $b['level'];
        });
    }

    /**
     * Merges logs from another Logging instance.
     */
    public function merge(Logging $other): void
    {
        foreach ($other->logs as $key => $otherLogs) {
            if (!isset($this->logs[$key])) {
                $this->logs[$key] = [];
            }

            $this->logs[$key] = array_merge($this->logs[$key], $otherLogs);
            
            // Sorting logs by level
            usort($this->logs[$key], function($a, $b) {
                return $a['level'] <=> $b['level'];
            });
        }
    }

    /**
     * Adds an error log entry.
     */
    public function addErrorLog(string $key, string $message, ?string $type = null): void
    {
        $this->addLog($key, $message, $type, self::LEVEL_ERROR);
    }

    /**
     * Retrieves the highest log level for a specific key.
     */
    public function getHighestLevelForKey(string $key): ?int
    {
        if (!isset($this->logs[$key]) || empty($this->logs[$key])) {
            return null;
        }

        return max(array_column($this->logs[$key], 'level'));
    }

    /**
     * Checks if any logs exist.
     */
    public function hasLogs(): bool
    {
        return !empty($this->logs);
    }

    /**
     * Checks if any error logs exist.
     */
    public function hasErrors(): bool
    {
        foreach ($this->logs as $logList) {
            foreach ($logList as $log) {
                if ($log['level'] === self::LEVEL_ERROR) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Retrieves logs filtered by a specific level.
     */
    public function getLogsByLevel(int $level): array
    {
        $result = [];
        
        foreach ($this->logs as $key => $logList) {
            $filteredLogs = array_filter($logList, function($log) use ($level) {
                return $log['level'] === $level;
            });
            
            if (!empty($filteredLogs)) {
                $result[$key] = array_values($filteredLogs);
            }
        }
        
        return $result;
    }

    /**
     * Converts logs to a JSON string representation.
     */
    public function __toString(): string
    {
        $flattened = [];
        
        foreach ($this->logs as $key => $logList) {
            foreach ($logList as $log) {
                $flattened[] = [
                    'key' => $key,
                    'message' => $log['message']
                ];
            }
        }
        
        return json_encode($flattened, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get all logs (for debugging or direct access)
     */
    public function getAllLogs(): array
    {
        return $this->logs;
    }
}

?>