<?php
namespace AppBundle\Controller;

use AppBundle\Entity\WeightRecord;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;

class BabyWeightController extends Controller {
    /**
     * @Route("/api/baby/weight")
     * @Method("POST")
     */
    function newAction(Request $request) {
        $logger = $this->get('logger');

        $data = $request->getContent();

        $weight = (int)trim(strip_tags($request->request->get('weight')));
        $date = trim(strip_tags($request->request->get('date')));

        $logger->error("weight:".$weight.', date'.$date);

        $record = new WeightRecord();
        $d = new \DateTime($date);
        // $d->__set($date);
        $record->setDate($d);
        $record->setWeight($weight);

        $em = $this->getDoctrine()->getManager();
        $em->persist($record);
        $em->flush();
        
        return new Response("<html><head><title>Create record OK</title></head><body>Done!</body></html>");
    }

    /**
     *
     * @Route("/api/baby/weight")
     * @Method("GET")
     */
    function getAction(Request $request) {
        $repository = $this->getDoctrine()
                ->getRepository('AppBundle:WeightRecord');

        $query = $repository->createQueryBuilder('w')
             ->where('w.id IS NOT NULL')->getQuery();
        $records = $query->getResult();
        
        foreach ($records as $rcd) {
            $rcds[] = array(
                'weight' => $rcd->getWeight(),
                'date' => $rcd->getDate()
            );
        }

        return $this->json($rcds);
    }
    
}

?>
