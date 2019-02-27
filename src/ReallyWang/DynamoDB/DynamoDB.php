<?php
/**
 * Created by PhpStorm.
 * User: reallywang
 * Date: 2019/2/25
 * Time: 4:47 PM
 */

namespace ReallyWang\DynamoDB;

use Aws;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\Exception\DynamoDbException;

class DynamoDB
{
    private $dynamodb = null;

    private $marshaler = null;

    public $error = '';

    private $table = '';

    private $key = [];

    private $condition = [];

    private $orCondition = [];

    private $conditionValue = [];

    private $mapping = [];

    private $filed = [];

    private $reservedWord = [
        'ABORT', 'ABSOLUTE', 'ACTION', 'ADD', 'AFTER', 'AGENT', 'AGGREGATE', 'ALL', 'ALLOCATE',
        'ALTER', 'ANALYZE', 'AND', 'ANY', 'ARCHIVE', 'ARE', 'ARRAY', 'AS', 'ASC', 'ASCII', 'ASENSITIVE', 'ASSERTION',
        'ASYMMETRIC', 'AT', 'ATOMIC', 'ATTACH', 'ATTRIBUTE', 'AUTH', 'AUTHORIZATION', 'AUTHORIZE', 'AUTO', 'AVG',
        'BACK', 'BACKUP', 'BASE', 'BATCH', 'BEFORE', 'BEGIN', 'BETWEEN', 'BIGINT', 'BINARY', 'BIT', 'BLOB', 'BLOCK',
        'BOOLEAN', 'BOTH', 'BREADTH', 'BUCKET', 'BULK', 'BY', 'BYTE', 'CALL', 'CALLED', 'CALLING', 'CAPACITY',
        'CASCADE', 'CASCADED', 'CASE', 'CAST', 'CATALOG', 'CHAR', 'CHARACTER', 'CHECK', 'CLASS', 'CLOB', 'CLOSE',
        'CLUSTER', 'CLUSTERED', 'CLUSTERING', 'CLUSTERS', 'COALESCE', 'COLLATE', 'COLLATION', 'COLLECTION', 'COLUMN',
        'COLUMNS', 'COMBINE', 'COMMENT', 'COMMIT', 'COMPACT', 'COMPILE', 'COMPRESS', 'CONDITION', 'CONFLICT', 'CONNECT',
        'CONNECTION', 'CONSISTENCY', 'CONSISTENT', 'CONSTRAINT', 'CONSTRAINTS', 'CONSTRUCTOR', 'CONSUMED', 'CONTINUE',
        'CONVERT', 'COPY', 'CORRESPONDING', 'COUNT', 'COUNTER', 'CREATE', 'CROSS', 'CUBE', 'CURRENT', 'CURSOR', 'CYCLE',
        'DATA', 'DATABASE', 'DATE', 'DATETIME', 'DAY', 'DEALLOCATE', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT',
        'DEFERRABLE', 'DEFERRED', 'DEFINE', 'DEFINED', 'DEFINITION', 'DELETE', 'DELIMITED', 'DEPTH', 'DEREF', 'DESC',
        'DESCRIBE', 'DESCRIPTOR', 'DETACH', 'DETERMINISTIC', 'DIAGNOSTICS', 'DIRECTORIES', 'DISABLE', 'DISCONNECT',
        'DISTINCT', 'DISTRIBUTE', 'DO', 'DOMAIN', 'DOUBLE', 'DROP', 'DUMP', 'DURATION', 'DYNAMIC', 'EACH', 'ELEMENT',
        'ELSE', 'ELSEIF', 'EMPTY', 'ENABLE', 'END', 'EQUAL', 'EQUALS', 'ERROR', 'ESCAPE', 'ESCAPED', 'EVAL', 'EVALUATE',
        'EXCEEDED', 'EXCEPT', 'EXCEPTION', 'EXCEPTIONS', 'EXCLUSIVE', 'EXEC', 'EXECUTE', 'EXISTS', 'EXIT', 'EXPLAIN',
        'EXPLODE', 'EXPORT', 'EXPRESSION', 'EXTENDED', 'EXTERNAL', 'EXTRACT', 'FAIL', 'FALSE', 'FAMILY', 'FETCH',
        'FIELDS', 'FILE', 'FILTER', 'FILTERING', 'FINAL', 'FINISH', 'FIRST', 'FIXED', 'FLATTERN', 'FLOAT', 'FOR',
        'FORCE', 'FOREIGN', 'FORMAT', 'FORWARD', 'FOUND', 'FREE', 'FROM', 'FULL', 'FUNCTION', 'FUNCTIONS', 'GENERAL',
        'GENERATE', 'GET', 'GLOB', 'GLOBAL', 'GO', 'GOTO', 'GRANT', 'GREATER', 'GROUP', 'GROUPING', 'HANDLER', 'HASH',
        'HAVE', 'HAVING', 'HEAP', 'HIDDEN', 'HOLD', 'HOUR', 'IDENTIFIED', 'IDENTITY', 'IF', 'IGNORE', 'IMMEDIATE',
        'IMPORT', 'IN', 'INCLUDING', 'INCLUSIVE', 'INCREMENT', 'INCREMENTAL', 'INDEX', 'INDEXED', 'INDEXES',
        'INDICATOR', 'INFINITE', 'INITIALLY', 'INLINE', 'INNER', 'INNTER', 'INOUT', 'INPUT', 'INSENSITIVE', 'INSERT',
        'INSTEAD', 'INT', 'INTEGER', 'INTERSECT', 'INTERVAL', 'INTO', 'INVALIDATE', 'IS', 'ISOLATION', 'ITEM', 'ITEMS',
        'ITERATE', 'JOIN', 'KEY', 'KEYS', 'LAG', 'LANGUAGE', 'LARGE', 'LAST', 'LATERAL', 'LEAD', 'LEADING', 'LEAVE',
        'LEFT', 'LENGTH', 'LESS', 'LEVEL', 'LIKE', 'LIMIT', 'LIMITED', 'LINES', 'LIST', 'LOAD', 'LOCAL', 'LOCALTIME',
        'LOCALTIMESTAMP', 'LOCATION', 'LOCATOR', 'LOCK', 'LOCKS', 'LOG', 'LOGED', 'LONG', 'LOOP', 'LOWER', 'MAP',
        'MATCH', 'MATERIALIZED', 'MAX', 'MAXLEN', 'MEMBER', 'MERGE', 'METHOD', 'METRICS', 'MIN', 'MINUS', 'MINUTE',
        'MISSING', 'MOD', 'MODE', 'MODIFIES', 'MODIFY', 'MODULE', 'MONTH', 'MULTI', 'MULTISET', 'NAME', 'NAMES',
        'NATIONAL', 'NATURAL', 'NCHAR', 'NCLOB', 'NEW', 'NEXT', 'NO', 'NONE', 'NOT', 'NULL', 'NULLIF', 'NUMBER',
        'NUMERIC', 'OBJECT', 'OF', 'OFFLINE', 'OFFSET', 'OLD', 'ON', 'ONLINE', 'ONLY', 'OPAQUE', 'OPEN', 'OPERATOR',
        'OPTION', 'OR', 'ORDER', 'ORDINALITY', 'OTHER', 'OTHERS', 'OUT', 'OUTER', 'OUTPUT', 'OVER', 'OVERLAPS',
        'OVERRIDE', 'OWNER', 'PAD', 'PARALLEL', 'PARAMETER', 'PARAMETERS', 'PARTIAL', 'PARTITION', 'PARTITIONED',
        'PARTITIONS', 'PATH', 'PERCENT', 'PERCENTILE', 'PERMISSION', 'PERMISSIONS', 'PIPE', 'PIPELINED', 'PLAN',
        'POOL', 'POSITION', 'PRECISION', 'PREPARE', 'PRESERVE', 'PRIMARY', 'PRIOR', 'PRIVATE', 'PRIVILEGES',
        'PROCEDURE', 'PROCESSED', 'PROJECT', 'PROJECTION', 'PROPERTY', 'PROVISIONING', 'PUBLIC', 'PUT', 'QUERY',
        'QUIT', 'QUORUM', 'RAISE', 'RANDOM', 'RANGE', 'RANK', 'RAW', 'READ', 'READS', 'REAL', 'REBUILD', 'RECORD',
        'RECURSIVE', 'REDUCE', 'REF', 'REFERENCE', 'REFERENCES', 'REFERENCING', 'REGEXP', 'REGION', 'REINDEX',
        'RELATIVE', 'RELEASE', 'REMAINDER', 'RENAME', 'REPEAT', 'REPLACE', 'REQUEST', 'RESET', 'RESIGNAL', 'RESOURCE',
        'RESPONSE', 'RESTORE', 'RESTRICT', 'RESULT', 'RETURN', 'RETURNING', 'RETURNS', 'REVERSE', 'REVOKE', 'RIGHT',
        'ROLE', 'ROLES', 'ROLLBACK', 'ROLLUP', 'ROUTINE', 'ROW', 'ROWS', 'RULE', 'RULES', 'SAMPLE', 'SATISFIES', 'SAVE',
        'SAVEPOINT', 'SCAN', 'SCHEMA', 'SCOPE', 'SCROLL', 'SEARCH', 'SECOND', 'SECTION', 'SEGMENT', 'SEGMENTS', 'SELECT',
        'SELF', 'SEMI', 'SENSITIVE', 'SEPARATE', 'SEQUENCE', 'SERIALIZABLE', 'SESSION', 'SET', 'SETS', 'SHARD', 'SHARE',
        'SHARED', 'SHORT', 'SHOW', 'SIGNAL', 'SIMILAR', 'SIZE', 'SKEWED', 'SMALLINT', 'SNAPSHOT', 'SOME', 'SOURCE',
        'SPACE', 'SPACES', 'SPARSE', 'SPECIFIC', 'SPECIFICTYPE', 'SPLIT', 'SQL', 'SQLCODE', 'SQLERROR', 'SQLEXCEPTION',
        'SQLSTATE', 'SQLWARNING', 'START', 'STATE', 'STATIC', 'STATUS', 'STORAGE', 'STORE', 'STORED', 'STREAM',
        'STRING', 'STRUCT', 'STYLE', 'SUB', 'SUBMULTISET', 'SUBPARTITION', 'SUBSTRING', 'SUBTYPE', 'SUM', 'SUPER',
        'SYMMETRIC', 'SYNONYM', 'SYSTEM', 'TABLE', 'TABLESAMPLE', 'TEMP', 'TEMPORARY', 'TERMINATED', 'TEXT', 'THAN',
        'THEN', 'THROUGHPUT', 'TIME', 'TIMESTAMP', 'TIMEZONE', 'TINYINT', 'TO', 'TOKEN', 'TOTAL', 'TOUCH', 'TRAILING',
        'TRANSACTION', 'TRANSFORM', 'TRANSLATE', 'TRANSLATION', 'TREAT', 'TRIGGER', 'TRIM', 'TRUE', 'TRUNCATE', 'TTL',
        'TUPLE', 'TYPE', 'UNDER', 'UNDO', 'UNION', 'UNIQUE', 'UNIT', 'UNKNOWN', 'UNLOGGED', 'UNNEST', 'UNPROCESSED',
        'UNSIGNED', 'UNTIL', 'UPDATE', 'UPPER', 'URL', 'USAGE', 'USE', 'USER', 'USERS', 'USING', 'UUID', 'VACUUM',
        'VALUE', 'VALUED', 'VALUES', 'VARCHAR', 'VARIABLE', 'VARIANCE', 'VARINT', 'VARYING', 'VIEW', 'VIEWS', 'VIRTUAL',
        'VOID', 'WAIT', 'WHEN', 'WHENEVER', 'WHERE', 'WHILE', 'WINDOW', 'WITH', 'WITHIN', 'WITHOUT', 'WORK', 'WRAPPED',
        'WRITE', 'YEAR', 'ZONE'
    ];

    private $assignment = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'a1', 'b1', 'c1', 'd1', 'e1', 'f1', 'g1', 'h1', 'i1', 'j1', 'k1', 'l1', 'm1',
        'n1', 'o1', 'p1', 'q1', 'r1', 's1', 't1', 'u1', 'v1', 'w1', 'x1', 'y1', 'z1', 'a2', 'b2', 'c2', 'd2', 'e2', 'f2',
        'g2', 'h2', 'i2', 'j2', 'k2', 'l2', 'm2', 'n2', 'o2', 'p2', 'q2', 'r2', 's2', 't2', 'u2', 'v2', 'w2', 'x2', 'y2',
        'z2'];

    private $mappingWord = ['aa', 'bb', 'cc', 'dd', 'ee', 'ff', 'gg', 'hh', 'ii', 'jj', 'kk', 'll', 'mm', 'nn', 'oo',
        'pp', 'qq', 'rr', 'ss', 'tt', 'uu', 'vv', 'ww', 'xx', 'yy', 'zz'];

    public function __construct($config)
    {
        $sdk = new Aws\Sdk($config);

        $this->dynamodb = $sdk->createDynamoDb();

        $this->marshaler = new Marshaler();
    }

    public function find()
    {
        $params = $this->recombination();

        try {
            $result = $this->dynamodb->getItem($params);

            $return = [];
            if ($result["Item"]) {
                $return = $this->marshaler->unmarshalItem($result["Item"]);
            }

            $this->clear();
            return $return;
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return null;
        }
    }

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function key(array $key)
    {
        $this->key = $this->marshaler->marshalJson(json_encode($key));
        return $this;
    }

    public function condition(array $condition, $isOr = false)
    {
        if (!$condition) {
            return $this;
        }
        $reservedWords = array_filter($condition, function ($k) {
            return in_array(strtoupper($k), $this->reservedWord);
        }, ARRAY_FILTER_USE_KEY);

        if ($reservedWords) {
            foreach ($reservedWords as $word => $reservedWord) {
                $this->mapping['#' . current($this->mappingWord)] = $word;
                $condition['#' . current($this->mappingWord)]     = $condition[$word];
                unset($condition[$word]);
                next($this->mappingWord);
            }
        }

        foreach ($condition as $key => $value) {
            if (!is_array($value)) {
                $this->condition[]                                      = "{$key} = :" . current($this->assignment);
                $this->conditionValue[':' . current($this->assignment)] = $value;
                if ($isOr) {
                    $this->orCondition[] = $key;
                }
            } else {
                if (in_array($value[0], ['<', '>', '>=', '<='])) {
                    $this->condition[]                                      = "{$key} {$value[0]} :" . current($this->assignment);
                    $this->conditionValue[':' . current($this->assignment)] = $value[1];
                    if ($isOr) {
                        $this->orCondition[] = $key;
                    }
                } elseif ($value[0] == 'between') {
                    $sql                                                    = "{$key} {$value[0]} :" . current($this->assignment);
                    $this->conditionValue[':' . current($this->assignment)] = $value[1];
                    next($this->assignment);

                    $sql                                                    .= " and :" . current($this->assignment);
                    $this->conditionValue[':' . current($this->assignment)] = $value[2];

                    $this->condition[] = $sql;
                    if ($isOr) {
                        $this->orCondition[] = $key;
                    }
                } else {
                    return $this;
                }
            }
            next($this->assignment);
        }
        return $this;
    }

    public function orCondition(array $condition)
    {
        return $this->condition($condition, true);
    }

    public function field(array $field)
    {
        $this->filed = array_unique(array_merge($this->filed, $field));

        $reservedWords = array_filter($this->filed, function ($value) {
            return in_array(strtoupper($value), $this->reservedWord);
        });

        if ($reservedWords) {
            if ($this->mapping) {
                $newMapping    = array_filter($this->filed, function ($item) {
                    return !in_array($item, $this->mapping);
                });
                $reservedWords = $newMapping;
            }
            foreach ($reservedWords as $key => $reservedWord) {
                $this->mapping['#' . current($this->mappingWord)] = $reservedWord;
                $this->filed[]                                    = '#' . current($this->mappingWord);
                unset($this->filed[$key]);
                next($this->mappingWord);
            }
        }
        return $this;
    }

    public function count($column = null)
    {
        if ($column) {
            $this->filed = [$column];
        }
        $params = $this->recombination(false, true);

        try {
            $result = $this->dynamodb->scan($params);

            $count = count($result['Items']);

            $this->clear();
            return $count;
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return null;
        }
    }

    public function get()
    {
        $params = $this->recombination(true);

        try {
            $result = $this->dynamodb->query($params);
            $return = [];
            if ($result) {
                foreach ($result['Items'] as $item) {
                    $return[] = $this->marshaler->unmarshalItem($item);
                }
            }

            $this->clear();
            return $return;
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return null;
        }
    }

    public function scan()
    {
        $params = $this->recombination(false, true);

        try {
            $result = $this->dynamodb->scan($params);

            $return = [];
            foreach ($result["Items"] as $array) {
                $return[] = $this->marshaler->unmarshalItem($array);
            }

            $this->clear();
            return $return;
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return null;
        }
    }

    public function update($value = [])
    {
        if (!$value) {
            return false;
        }

        $updateValue = [];
        foreach ($value as $k => $v) {
            $updateValue[]                                          = "{$k} = :" . current($this->assignment);
            $this->conditionValue[':' . current($this->assignment)] = $v;
            next($this->assignment);
        }
        $updateSql = 'set ' . implode(',', $updateValue);

        $params = $this->recombination();

        $params['UpdateExpression'] = $updateSql;

        try {
            $result = $this->dynamodb->updateItem($params);

            $this->clear();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return false;
        }
    }

    public function step(string $key, bool $action = true, int $num = 1)
    {
        if (!$key) {
            return false;
        }
        if ($action) {
            $sign = '+';
        } else {
            $sign = '-';
        }
        $updateValue                                            = "{$key} = {$key} {$sign} :" . current($this->assignment);
        $this->conditionValue[':' . current($this->assignment)] = $num;
        next($this->assignment);

        $updateSql = 'set ' . $updateValue;

        $params = $this->recombination();

        $params['UpdateExpression'] = $updateSql;

        try {
            $result = $this->dynamodb->updateItem($params);

            $this->clear();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return false;
        }
    }

    public function remove($value = [])
    {
        if (!$value) {
            return false;
        }

        $updateValue = [];
        foreach ($value as $v) {
            $updateValue[] = $v;
        }
        $updateSql = 'remove ' . implode(',', $updateValue);

        $params = $this->recombination();

        $params['UpdateExpression'] = $updateSql;

        try {
            $result = $this->dynamodb->updateItem($params);

            $this->clear();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return false;
        }
    }

    public function delete()
    {
        $params = $this->recombination();

        try {
            $result = $this->dynamodb->deleteItem($params);

            $this->clear();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return false;
        }
    }

    public function insert(array $value)
    {
        if (!$value) {
            return false;
        }
        try {
            if (isset($value[0])) {
                foreach ($value as $v) {
                    $return = $this->insert($v);
                }
                return $return;
            } else {
                $params = $this->recombination();

                $params['Item'] = $this->marshaler->marshalJson(json_encode($value));

                $result = $this->dynamodb->putItem($params);

                $this->clear();
                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (DynamoDbException $e) {
            $this->error = $e->getMessage();
            $this->clear();
            return false;
        }
    }

    public function getError()
    {
        return $this->error == '' ? null : $this->error;
    }

    private function recombination($hasKey = false, $useScan = false)
    {
        $params = [];

        if ($this->table) {
            $params['TableName'] = $this->table;
        }

        if ($this->key) {
            $params['Key'] = $this->key;
        }

        if ($this->condition) {
            $condition = implode(' and ', $this->condition);
            if ($this->orCondition) {
                foreach ($this->orCondition as $value) {
                    $condition = str_replace('and ' . $value, 'or ' . $value, $condition);
                }
            }
            if ($hasKey) {
                $params['KeyConditionExpression'] = $condition;
            } else if ($useScan) {
                $params['FilterExpression'] = $condition;
            } else {
                $params['ConditionExpression'] = $condition;
            }
        }

        if ($this->conditionValue) {
            $params['ExpressionAttributeValues'] = $this->marshaler->marshalJson(json_encode($this->conditionValue));
        }

        if ($this->mapping) {
            $params['ExpressionAttributeNames'] = $this->mapping;
        }

        if ($this->filed) {
            $params['ProjectionExpression'] = implode(',', $this->filed);
        }
        return $params;
    }

    private function clear()
    {
        $this->table          = '';
        $this->mapping        = [];
        $this->key            = [];
        $this->condition      = [];
        $this->conditionValue = [];
        $this->filed          = [];
        $this->orCondition    = [];
        reset($this->assignment);
        reset($this->mappingWord);
    }

    private function createTable()
    {
        // TODO 目前没有需求，以后需要的时候再写
    }

    private function deleteTable()
    {
        // TODO 目前没有需求，以后需要的时候再写
    }
}