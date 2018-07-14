<?php

namespace Aham\CourseCatalog;

use Everyman\Neo4j;
use Everyman\Neo4j\Cypher;
use Aham\Exceptions\Neo4jNodeNotFound;

/*
    NOTE:
        Neo4j naming convention:

        Labels: UpperCamelCase (a.k.a. PascalCase)
        Relationships: CAPITALIZED_WITH_UNDERSCORE
        Property key names: lowerCamelCase or snake_case
*/

class NeoHelper
{
    public $client;

    public function __construct()
    {

        $this->client = new \Everyman\Neo4j\Client(env('NEO_HOST'), env('NEO_PORT'));

        if (env('NEO_HTTPS', true)) {
            $this->client->getTransport()
            ->useHttps()
            ->setAuth(env('NEO_USERNAME'), env('NEO_PASSWORD'));
        } else {
            $this->client->getTransport()
            ->setAuth(env('NEO_USERNAME'), env('NEO_PASSWORD'));
        }

        //dd($this->client);
    }

    public function getConnection()
    {
        return $this->client;
    }

    // NODES

    /*
        EX:-
            CREATE (a:Person {name:"Théo Gauchoux"}) RETURN a
            create (n:nodename {propertyKey:'propertyValue'}) return n;
    */
    public function createNode($nodeLabel, $properties)
    {
        if (is_array($properties)) {
            $properties = $this->removeQuotes($properties);
        }

        $queryTemplate = "CREATE (n:$nodeLabel $properties)";
        $query = new Cypher\Query($this->client, $queryTemplate);

        $query->getResultSet();
    }

    public function getNode($nodeLabel, $properties)
    {
        if (is_array($properties)) {
            $properties = $this->removeQuotes($properties);
        }
        $queryTemplate = "MATCH (a:$nodeLabel $properties) RETURN a";
        $query = new Cypher\Query($this->client, $queryTemplate);
        if ($query->getResultSet()->count()) {
            return $query->getResultSet()->offsetGet(0)->current()->getProperties();
        } else {
            throw new Neo4jNodeNotFound();
        }
    }

    public function getNodeWhere($nodeLabel, $propertyKey, $propertyValue)
    {
        $queryTemplate = "MATCH (n:$nodeLabel)
                          WHERE n.$propertyKey = $propertyValue
                          RETURN n";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $resultArray = ($query->getResultSet()->getData(0));

        return $resultArray;
    }

    /*
        EX:- TODO: pass only $value and remove propertykey
            MATCH (p:Person) WHERE p.name = "Théo Gauchoux" SET p.age = 23
            MATCH (n) where n.year="non" RETURN n LIMIT 25
            MATCH (n) WHERE n.year = "non" SET n.year = "updated"
            MATCH (n:{year:"non"}) set n.year = 'updatedvalue'
    */
    public function update($nodeLabel, $propertyKey, $value, $updateValues)
    {
        // var_dump($updateValues); die;

        $queryTemplate = "MATCH (n:$nodeLabel) 
                          WHERE n.$propertyKey = $value
                          SET ";

        $append = [];

        foreach ($updateValues as $key => $value) {
            $append[] = "n.$key = '$value'";
        }

        $queryTemplate = $queryTemplate . implode(',', $append);
        
        $query = new Cypher\Query($this->client, $queryTemplate);

        //return $result;
        $result = $query->getResultSet();
        return $result;
    }

    /*
        Ex:-
            MATCH (p:Person)-[relationship]-() WHERE p.name = "Théo Gauchoux" DELETE relationship, p
            #find and delete node/relationship.
                match (node {propertykey: "propertyvalue"})
            #and delete the values
                MATCH (z) WHERE z.year = 'changed'
                DETACH DELETE z
        TODO:- need to remove quotes for $matchValue
    */
    public function delete($nodeLabel, $propertyKey, $matchValue)
    {
        $queryTemplate = "MATCH (n:$nodeLabel) 
                          WHERE n.$propertyKey = $matchValue
                          DETACH DELETE n";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();

        return $result;
    }

    public function deleteAllNodesAndRelationships()
    {
        $queryTemplate = "MATCH (n) DETACH DELETE n";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();

        return $result;
    }

    /*
     MATCH (a:Topic {}),(b:Topic {})
     p = shortestPath((a)-[:REQUIRES|HAS*]->(b))
     RETURN length(p)
    */
    public function isShortestPathAB($sourceNode, $destinationNode, $sourceProperties, $destinationProperties)
    {
        if (is_array($sourceProperties) && is_array($destinationProperties)) {
            $sourceProperties = $this->removeQuotes($sourceProperties);
            $destinationProperties = $this->removeQuotes($destinationProperties);
        }

        $queryTemplate = "MATCH (a:$sourceNode $sourceProperties),(b:$destinationNode $destinationProperties),
                          p = shortestPath((a)-[:REQUIRES|HAS*]->(b))
                         RETURN length(p)";

        //dd($queryTemplate);
        $query = new Cypher\Query($this->client, $queryTemplate);

        $length = 0;
        if ($query->getResultSet()->count()) {
            $length = $query->getResultSet()->offsetGet(0)->current();
        }

        if ($length > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isShortestPathBA($sourceNode, $destinationNode, $sourceProperties, $destinationProperties)
    {
        if (is_array($sourceProperties) && is_array($destinationProperties)) {
            $sourceProperties = $this->removeQuotes($sourceProperties);
            $destinationProperties = $this->removeQuotes($destinationProperties);
        }

        $queryTemplate = "MATCH (a:$sourceNode $sourceProperties),(b:$destinationNode $destinationProperties), 
                          p = shortestPath((b)-[:REQUIRES|HAS*]->(a))
                         RETURN length(p)";

        $query = new Cypher\Query($this->client, $queryTemplate);

        $length = 0;
        if ($query->getResultSet()->count()) {
            $length = $query->getResultSet()->offsetGet(0)->current();
        }

        if ($length > 0) {
            return true;
        } else {
            return false;
        }
    }

    // RELATIONSHIPS

    /*
        Ex:-
            create a node,relationship then match the node then create relation between
            MATCH (u:User {username:'admin'}), (r:Role {name:'ROLE_WEB_USER'})
            CREATE (u)-[:HAS_ROLE]->(r)

            MATCH (a:Person),(b:Person)
            WHERE a.name = 'Node A' AND b.name = 'Node B'
            CREATE (a)-[r:RELTYPE]->(b)
            RETURN r

            RELATIONSHIPS
    */
    public function createRelation(
        $sourceLabel,
        $destinationLabel,
        $sourceKey,
        $sourceValue,
        $destinationKey,
        $destinationValue,
        $relationshipType
    ) {
        $queryTemplate = "MATCH (s:$sourceLabel),(t:$destinationLabel) 
                          WHERE s.$sourceKey = $sourceValue AND t.$destinationKey = $destinationValue
                          CREATE (s)-[r:$relationshipType]->(t)";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();
        // return $result;
    }

    /*
        Ex:-
            MATCH (a {name:"A"})-[r]-(b {name:"B"})
            SET r.P = "bar"

            MATCH (n:User {name:"foo"})-[r:REL]->(m:User {name:"bar"})
            CREATE (n)-[r2:NEWREL]->(m)

            // copy properties, if necessary
            SET r2 = r
            WITH r
            DELETE r
    */
    public function updateRelation(
        $sourceNode,
        $sourceProperty,
        $existingRelationship,
        $destinationNode,
        $destinationProperty,
        $newRelationshipType
    ) {
        $queryTemplate = "MATCH (s:$sourceNode $sourceProperty)-[r:$existingRelationship]->(t:$destinationNode $destinationProperty) 
                          CREATE (s)-[r2:$newRelationshipType]->(t)
                          DELETE r";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();
    }

    public function deleteRelation(
        $sourceNode,
        $sourceProperty,
        $existingRelationship,
        $destinationNode,
        $destinationProperty
    ) {
        // matching with some property will be safeer
        $queryTemplate = "MATCH (s:$sourceNode $sourceProperty)-[r:$existingRelationship]-(t:$destinationNode $destinationProperty) 
                          DELETE r";

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();
    }

    public function runQuery($queryTemplate){

        $query = new Cypher\Query($this->client, $queryTemplate);
        $result = $query->getResultSet();

        // ddd($query->getResultSet());

        return $result;

    }

    /*
        Note:-
            #encode the array
                json_encode(['year' => 'value'])
            #for removeing double quote
                preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/','$1:',$somearray)
    */
    public function removeQuotes($array)
    {
        return  preg_replace('/"([a-zA-Z]+[a-zA-Z0-9_]*)":/', '$1:', json_encode($array, JSON_UNESCAPED_SLASHES));
    }

    public function getKey($array)
    {
        foreach ($array as $key => $val) {
            $key1 = $key;
        }
        return $key1;
    }

    public function getValue($array)
    {
        foreach ($array as $key => $val) {
            $value = $val;
        }
        return $value;
    }

    public function propertyValueCheck($propertyValue)
    {
        if (gettype($propertyValue) == 'string') {
            // api can not delete property values like string with blank space
            throw new \Exception('propertyvalue:=' . $propertyValue . ' \tPass only Id or pass string with no blank space');
        }
    }

    //TODO::
    // search relationship
    // queries, error handling,warrnings, dynamic querying.
}
