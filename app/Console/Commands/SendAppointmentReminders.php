<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Mail\AppointmentReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Find appointments with reminders due
        $appointments = Appointment::whereNotNull('reminder_settings')
            ->get();

        foreach ($appointments as $appointment) {
            $reminderSettings = json_decode($appointment->reminder_settings, true);

            if ($reminderSettings) {
                foreach ($reminderSettings as $setting) {
                    $reminderTime = Carbon::parse($appointment->date . ' ' . $appointment->time)->sub($setting['value'], $setting['unit']);

                    if ($now->greaterThanOrEqualTo($reminderTime) && $now->lessThan($reminderTime->copy()->addMinutes(15))) { // Send reminder within a 15-minute window
                        // Send email to assigned lawyers
                        foreach ($appointment->lawyers as $lawyer) {
                            Mail::to($lawyer->email)->send(new AppointmentReminder($appointment));
                            $this->info("Sent reminder for appointment '{$appointment->title}' to {$lawyer->email}");
                        }
                    }
                }
            }
        }
    }
}
