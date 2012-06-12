<?php
class ItsightHttpHeaders implements ArrayAccess, Iterator, Countable {

    protected $headers;


    protected $map;


    public function __construct( $headers = array() ) {
        $this->merge($headers);
    }


    public function merge( $headers ) {
        foreach ( $headers as $name => $value ) {
            $this[$name] = $value;
        }
    }


    protected function canonical( $name ) {
        return strtolower(trim($name));
    }

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists( $offset ) {
        return isset($this->headers[$this->canonical($offset)]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet( $offset ) {
        $canonical = $this->canonical($offset);
        if ( isset($this->headers[$canonical]) ) {
            return $this->headers[$canonical];
        } else {
            return null;
        }
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet( $offset, $value ) {
        $canonical = $this->canonical($offset);
        $this->headers[$canonical] = $value;
        $this->map[$canonical] = $offset;
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset( $offset ) {
        $canonical = $this->canonical($offset);
        unset($this->headers[$canonical], $this->map[$canonical]);
    }

    /**
     * Countable: Count
     */
    public function count() {
        return count($this->headers);
    }

    /**
     * Iterator: Rewind
     */
    public function rewind() {
        reset($this->headers);
    }

    /**
     * Iterator: Current
     */
    public function current() {
        return current($this->headers);
    }

    /**
     * Iterator: Key
     */
    public function key() {
        $key = key($this->headers);
        return $this->map[$key];
    }

    /**
     * Iterator: Next
     */
    public function next() {
        return next($this->headers);
    }

    /**
     * Iterator: Valid
     */
    public function valid() {
        return current($this->headers) !== false;
    }
}
?>