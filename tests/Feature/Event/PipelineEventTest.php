<?php


namespace Tests\Feature\Event;

use Tests\Feature\BasicPageTestCase;


class PipelineEventTest extends BasicPageTestCase
{

    public function  testFlowStartedEvent()
    {
        $this->withoutExceptionHandling();
        $token = $this->getHeaderWithApiToken();
        $schoolUuId = $this->getHeaderWithUuidForSchool($this->getStudent());
        $header = array_merge($token, $schoolUuId);
        $data = ['action' => ['flow_id' => 2]];

        $response = $this->post(route('api.pipeline.flow.start'), $data, $header);
        dd($response);
    }


}
