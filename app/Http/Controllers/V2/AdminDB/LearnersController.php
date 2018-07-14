<?php

namespace Aham\Http\Controllers\V2\AdminDB;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\LearnerRepository;
use Aham\CreditsEngine\Sync;

use Aham\Models\SQL\Location;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\TransformersV2\LearnerTransformer;

class LearnersController extends Controller
{
    private $learner;

    public function __construct(LearnerRepository $learner)
    {
        $this->learner = $learner;
    }

    public function index()
    {
        $model = $this->learner->filter(
            request()->get('input', ''),
            request()->get('sort', 'created_at_desc')
        );

        $paginator = $model->paginate(20);

        $topics = $paginator->getCollection();

        $resource = new Fractal\Resource\Collection($topics, new LearnerTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData($resource)->toArray();
    }

    public function update($id)
    {
      $learner = $this->learner->find($id);
      $user = $learner->user;
      $user->fill(request()->only('name'));
      $user->save();

      $learner->hubs()->sync(request()->get('preferred_locations'));

      return \Response::json(array(
          'success' => true
      ), 200);
    }

    public function credits($id)
    {
        $learner = $this->learner->find($id);
        $user = $learner->user;
        $creditsUsage = [];
        
        $credits = $user->purchasedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['purchased'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'price' => $credit->price,
               'added_on' => $credit->added_on,
               'remarks' => $credit->remarks,
               'method' => $credit->method,
               'currency_type' => $credit->currency_type,
               'hub' => $credit->of->name,
            ];
        }

        $credits = $user->promotionalCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['promotional'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'coupon' => $credit->coupon,
                'added_on' => $credit->added_on->format('jS M Y'),
               'hub' => $credit->of->name,
               'purchased_item' => $credit->purchasedItem
            ];
        }

        $credits = $user->usedCredits()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['used'][] = [
               'id' => $credit->id,
               'credits' => $credit->credits,
               'bucket_id' => $credit->bucket_id,
               'credits_type' => $credit->credits_type,
               'refunded' => $credit->credits - $credit->refund_remaining,
               'refund_remaining' => $credit->refund_remaining,
               'class' => $credit->of->code,
            ];
        }

        $credits = $user->creditBuckets()->orderBy('created_at', 'asc')->get();

        foreach ($credits as $credit) {
            $creditsUsage['buckets'][] = [
               'id' => $credit->id,
               'purchased_total' => $credit->purchased_total,
               'promotional_total' => $credit->promotional_total,
               'hub_only_total' => $credit->hub_only_total,
               'total_credits' => $credit->total_credits,
               'purchased_remaining' => $credit->purchased_remaining,
               'hub_only_remaining' => $credit->hub_only_remaining,
               'promotional_remaining' => $credit->promotional_remaining,
               'total_remaining' => $credit->total_remaining,
               'hub_credits_type' => $credit->hub_credits_type,
               'hub_credits_type' => $credit->hub_credits_type,
               'currency_type' => $credit->currency_type,
               'hub' => $credit->hub->name,
            ];
        }

        $creditsUsage['learner'] = $learner;
        $creditsUsage['user'] = $user;
        $creditsUsage['locations'] = Location::get();
        $creditsUsage['lifetime'] = $learner->lifetimeOffer;

        return $creditsUsage;
    }

    public function syncCredits($id)
    {
        $sync = new Sync();
        $sync->learner($id);

        return $id;
    }


}
