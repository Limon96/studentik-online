<?php
class ControllerStartupReferral extends Controller
{
    public function index()
    {
        if (isset($this->request->get['ref'])) {
            $this->session->data['referral_code'] = $this->request->get['ref'];
        }
    }

}
