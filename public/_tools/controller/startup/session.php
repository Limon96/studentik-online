<?php
class ControllerStartupSession extends Controller {
	public function index() {
        $session_id = ParserSession::getSessionId();

        $this->session->start($session_id);
	}
}
