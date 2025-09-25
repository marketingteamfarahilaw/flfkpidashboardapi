<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeadDocket_Model extends CI_Model {

    public function saveOrUpdate($data) {
        $full_name = trim($data['Full Name'] ?? '');
        if (!$full_name) return null;

        $nameParts = preg_split('/\s+/', $full_name);
        $firstname = $nameParts[0];
        $lastname = end($nameParts);

        $mapped = [
            'full_name'                   => $full_name,
            'mobile_phone'               => $data['Mobile Phone'] ?? null,
            'status'                     => $data['Status'] ?? null,
            'substatus'                  => $data['SubStatus'] ?? null,
            'case_type'                  => $data['Case Type'] ?? null,
            'marketing_source'           => $data['Marketing Source'] ?? null,
            'created_date'               => $this->formatDateTime($data['Created Date'] ?? null),
            'incident_date'              => $this->formatDate($data['Incident Date'] ?? null),
            'last_status_change_date'    => $this->formatDateTime($data['Last Status Change Date'] ?? null),
            'last_update_date'           => $this->formatDateTime($data['Last Update Date'] ?? null),
            'last_note_date'             => $this->formatDateTime($data['Last Note Date'] ?? null),
            'last_note'                  => $data['Last Note'] ?? null,
            'lead_outcome'               => $data['Lead Outcome'] ?? null,
            'call_outcome'               => $data['Call Outcome'] ?? null,
            'not_interested_disposition' => $data['NOT INTERESTED Disposition'] ?? null,
            'not_responsive_disposition' => $data['NOT RESPONSIVE Disposition'] ?? null,
            'intake_completed_by'        => $data['Name of team member who completed the intake'] ?? null,
            'initial_call_taken_by'      => $data['Name of team member who took the initial call'] ?? null,
            'open_disposition'           => $data['OPEN Disposition'] ?? null,
            'referred_out_disposition'   => $data['REFERRED OUT Disposition'] ?? null,
            'rejected_disposition'       => $data['REJECTED Disposition'] ?? null,
            'signed_disposition'         => $data['SIGNED Disposition'] ?? null,
        ];

        $this->db->where('full_name', $full_name);
        $existing = $this->db->get('leads_tracker')->row();

        if ($existing) {
            $this->db->where('full_name', $full_name);
            $this->db->update('leads_tracker', $mapped);
            return 'update';
        } else {
            $this->db->insert('leads_tracker', $mapped);
            return 'insert';
        }
    }

    private function formatDateTime($value) {
        if (empty($value)) return null;
        $date = date_create($value);
        return $date ? date_format($date, 'Y-m-d H:i:s') : null;
    }

    private function formatDate($value) {
        if (empty($value)) return null;
        $date = date_create($value);
        return $date ? date_format($date, 'Y-m-d') : null;
    }
}
