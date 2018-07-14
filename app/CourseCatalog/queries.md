MATCH (n) RETURN n;
// Delete all nodes
MATCH (n) DETACH DELETE n;
// Find a node
MATCH (n:SubCategory)-[r]-(m) WHERE n.id = 296 RETURN n,r,m;



MATCH p=(a)-[:HAS*0..3]->(m {id:3})-[:HAS*0..3]->(n)
WITH COLLECT(p) AS ps
RETURN ps;

MATCH p=(a)-[:HAS*0..3]->(m {id:832})-[:HAS*0..3]->(n)
WITH COLLECT(p) AS ps
CALL apoc.convert.toTree(ps) yield value
RETURN value;

MATCH p=(a)-[:HAS*0..3]->(m)-[:HAS*0..3]->(n)
WHERE m.id = 8 OR m.id = 7
WITH COLLECT(p) AS ps
CALL apoc.convert.toTree(ps) yield value
RETURN value;


MATCH p=(m)-[:HAS*0..3]->(n:Topic)
WHERE m.id = 512 OR m.id = 42 OR m.id = 4 OR m.id = 52
WITH NODES(p) AS nodes
WITH FILTER(node in nodes WHERE (node:Topic)) as pathStreets
RETURN pathStreets