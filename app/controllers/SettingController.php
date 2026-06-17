<?php
// app/controllers/SettingController.php

class SettingController extends BaseController {
    private SettingModel $settingModel;

    public function __construct() {
        $this->settingModel = new SettingModel();
    }

    public function index(): void {
        $settings = $this->settingModel->getAllSettings();
        $this->view('admin/settings/index', compact('settings'));
    }

    public function update(): void {
        if (!$this->isPost()) {
            $this->redirect('/index.php?page=settings');
        }

        // Process structured operational hours
        $opsDays = $_POST['ops_day'] ?? [];
        $opsStarts = $_POST['ops_start'] ?? [];
        $opsEnds = $_POST['ops_end'] ?? [];
        $opsData = [];
        for ($i = 0; $i < count($opsDays); $i++) {
            $day = trim($opsDays[$i] ?? '');
            if ($day !== '') {
                $start = trim($opsStarts[$i] ?? '08:00');
                $end = trim($opsEnds[$i] ?? '16:00');
                // Format time as 08.00 - 16.00
                $time = str_replace(':', '.', $start) . ' - ' . str_replace(':', '.', $end);
                $opsData[] = ['day' => $day, 'time' => $time];
            }
        }
        $operational_hours_json = json_encode($opsData);

        $settings = [
            'contact_phone'     => $this->input('contact_phone'),
            'contact_email'     => $this->input('contact_email'),
            'contact_address'   => $this->input('contact_address'),
            'operational_hours' => $operational_hours_json,
            'info_board'        => $this->input('info_board'),
        ];

        $this->settingModel->updateMany($settings);

        flashMessage('success', 'Pengaturan kontak berhasil diperbarui!');
        $this->redirect('/index.php?page=settings');
    }
}
