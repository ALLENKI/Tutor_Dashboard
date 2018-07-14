<?php namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Unit;
use Aham\Models\SQL\TopicPrerequisite;
use Aham\Models\SQL\User;
use Aham\Models\SQL\Goal;

use Aham\Models\Graph\Topic as GTopic;
use Aham\Models\Graph\SubCategory as GSubCategory;
use Aham\Models\Graph\Subject as GSubject;
use Aham\Models\Graph\Category as GCategory;
use Aham\Models\Graph\User as GUser;

use GraphAware\Neo4j\Client\ClientBuilder;


class SyncNeo4J extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'aham:syncneo4j';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync Current DB to Neo4J';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

		$client = ClientBuilder::create()
		    ->addConnection('default', 'http://neo4j:secret@localhost:7474')
		    ->addConnection('bolt', 'bolt://neo4j:secret@localhost:7687')
		    ->build();

		// $result = $client->run("MATCH (a:Goal {name: 2})-[r:GOAL]->(b:Topic) RETURN r, a, b");
		$result = $client->run("MATCH (a:Goal {name: 2}), (b:Topic)-[:REQUIRES]->(n:Topic) WHERE (a)-[:GOAL]->(b) RETURN a, b, n");

		dd($result->getRecords());

		$client->run('MATCH (n:Goal) DELETE n');

		$goals = Goal::all();

		foreach($goals as $goal)
		{
			$client->run("CREATE (n:Goal {name: $goal->id})");

			foreach($goal->topics as $topic)
			{
				$client->run("MATCH (a:Goal {name: $goal->id}), (b:Topic {name: $topic->id}) CREATE (a)-[r:GOAL]->(b) RETURN a,b");
			}
		}

		$query = "MATCH (n:Topic) WHERE n.name IN [";

		$query .= implode(',',$goal->topics->pluck('id')->toArray());

		$query .= "] RETURN n";

		$result = $client->run($query);

		dd($result);

		$client->run('MATCH (n:Topic)<-[r:KNOWS]-(:Student) DELETE r');
		$client->run('MATCH (n:Topic)<-[r:REQUIRES]-(:Topic) DELETE r');
		$client->run('MATCH (n:Topic) DELETE n');
		$client->run('MATCH (n:Student) DELETE n');

		$topics = Topic::get();

		foreach($topics as $topic)
		{
			$client->run("CREATE (n:Topic {name: $topic->id})");
		}

		// // $topics = Topic::where('type','category')->get();

		foreach($topics as $topic)
		{

			foreach($topic->children as $child)
			{
				$this->line("Child: $topic->id : ".$child->id);

				$result = $client->run("MATCH (a:Topic {name: $topic->id}), (b:Topic {name: $child->id}) CREATE (a)-[r:REQUIRES]->(b) RETURN a,b");
			}

			foreach($topic->prerequisites as $child)
			{
				$client->run("MATCH (a:Topic {name: $topic->id}), (b:Topic {name: $child->id}) CREATE (a)-[r:REQUIRES]->(b) RETURN a,b;");
			}
			
		}

	}

}
