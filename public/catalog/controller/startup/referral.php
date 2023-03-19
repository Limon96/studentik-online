<?php
class ControllerStartupReferral extends Controller
{
    public function index()
    {
        if ($this->validate()) {
            $this->session->data['referral_code'] = $this->request->get['ref'];
        }
    }

    private function validate()
    {
        if (!isset($this->request->get['ref'])) {
            return false;
        }

        $white_list = [
            'microgadgets'
        ];

        if (!in_array($this->request->get['ref'], $white_list)) {
            return false;
        }

        return true;
    }

}
