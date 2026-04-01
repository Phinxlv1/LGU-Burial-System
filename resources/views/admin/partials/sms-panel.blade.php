{{--
    SMS Notification Panel
    Include this inside resources/views/permits/show.blade.php
    after the info-grid section and before the documents section.

    Requires: $permit (loaded with deceased, smsNotifications)
--}}

@php
    $lastSms = $permit->smsNotifications()->latest()->first();
    $contact = $permit->applicant_contact;
    $canSend = !empty($contact);
    $daysLeft = $permit->expiry_date ? now()->diffInDays($permit->expiry_date, false) : null;
    $isExpiringSoon = $permit->status === 'expiring';
    $isExpired = $permit->status === 'expired';
@endphp

<style>
.sms-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
.sms-head  { padding: .85rem 1.25rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
.sms-head-left { display: flex; align-items: center; gap: .5rem; }
.sms-head-title { font-size: 13px; font-weight: 700; color: #111827; }
.sms-head-sub { font-size: 11px; color: #9ca3af; }
.sms-body { padding: 1.1rem 1.25rem; display: flex; flex-direction: column; gap: 1rem; }
.sms-body-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* Warning Banner */
.sms-warning {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .85rem 1rem; border-radius: 8px;
    background: #fffbeb; border: 1px solid #fde68a;
}
.sms-warning.expired { background: #fff1f2; border-color: #fecaca; }
.sms-warning-icon { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; background: #fef3c7; }
.sms-warning.expired .sms-warning-icon { background: #fee2e2; }
.sms-warning-title { font-size: 13px; font-weight: 700; color: #92400e; }
.sms-warning.expired .sms-warning-title { color: #991b1b; }
.sms-warning-sub { font-size: 12px; color: #78350f; margin-top: 2px; }
.sms-warning.expired .sms-warning-sub { color: #b91c1c; }

/* Quick Send Buttons */
.sms-quick { display: flex; flex-wrap: wrap; gap: .4rem; }
.btn-sms-quick {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .42rem .9rem; border-radius: 6px; border: 1.5px solid;
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600;
    cursor: pointer; background: none; transition: all .15s; white-space: nowrap;
}
.btn-sms-quick:disabled { opacity: .4; cursor: not-allowed; }
.btn-sms-amber { border-color: #fcd34d; color: #92400e; background: #fffbeb; }
.btn-sms-amber:hover:not(:disabled) { background: #fef3c7; border-color: #f59e0b; }
.btn-sms-green { border-color: #6ee7b7; color: #065f46; background: #f0fdf4; }
.btn-sms-green:hover:not(:disabled) { background: #d1fae5; border-color: #10b981; }
.btn-sms-blue  { border-color: #93c5fd; color: #1e40af; background: #eff6ff; }
.btn-sms-blue:hover:not(:disabled)  { background: #dbeafe; border-color: #3b82f6; }

/* Log Table */
.sms-log { border: 1px solid #f3f4f6; border-radius: 8px; overflow: hidden; }
.sms-log table { width: 100%; border-collapse: collapse; }
.sms-log th { font-size: 9px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .07em; padding: .45rem .75rem; background: #fafafa; text-align: left; }
.sms-log td { font-size: 11px; color: #374151; padding: .5rem .75rem; border-top: 1px solid #f9fafb; }
.sms-log-empty { text-align: center; color: #d1d5db; padding: 1.25rem; font-size: 12px; }

.badge-sms-sent   { display: inline-flex; font-size: 9px; font-weight: 700; padding: 2px 7px; border-radius: 3px; background: #d1fae5; color: #065f46; }
.badge-sms-failed { display: inline-flex; font-size: 9px; font-weight: 700; padding: 2px 7px; border-radius: 3px; background: #fee2e2; color: #991b1b; }
.badge-sms-dev    { display: inline-flex; font-size: 9px; font-weight: 700; padding: 2px 7px; border-radius: 3px; background: #dbeafe; color: #1e40af; }

/* Custom message modal */
.sms-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 200; align-items: center; justify-content: center; padding: 1rem; }
.sms-modal-overlay.open { display: flex; }
.sms-modal { background: #fff; border-radius: 10px; width: 100%; max-width: 460px; box-shadow: 0 20px 60px rgba(0,0,0,.2); overflow: hidden; animation: smsModalIn .15s ease; margin: auto; }
@keyframes smsModalIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
.sms-modal-head { padding: .9rem 1.25rem; background: #1a2744; display: flex; align-items: center; justify-content: space-between; }
.sms-modal-head h3 { font-size: 14px; font-weight: 700; color: #fff; }
.sms-modal-close { background: none; border: none; cursor: pointer; color: rgba(255,255,255,.65); font-size: 20px; line-height: 1; transition: color .15s; }
.sms-modal-close:hover { color: #fff; }
.sms-modal-body { padding: 1.25rem; display: flex; flex-direction: column; gap: .85rem; }
.sms-modal-foot { padding: .85rem 1.25rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: .6rem; }
.sms-char-count { font-size: 11px; color: #9ca3af; text-align: right; margin-top: 2px; }
.sms-char-count.warn { color: #ef4444; font-weight: 600; }
.sms-no-contact {
    display: flex; align-items: center; gap: .6rem;
    padding: .75rem 1rem; background: #fafafa; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 12px; color: #6b7280;
}
</style>

<div class="sms-panel">
    <div class="sms-head">
        <div class="sms-head-left">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
            </svg>
            <span class="sms-head-title">SMS Notifications</span>
            <span class="sms-head-sub">— {{ $permit->smsNotifications()->count() }} sent</span>
        </div>
        <span style="font-size:11px;color:#9ca3af">Via Vonage · PH SMS</span>
    </div>

    <div class="sms-body">

        {{-- Expiry warning banner --}}
        @if($isExpired)
        <div class="sms-warning expired">
            <div class="sms-warning-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#991b1b" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div>
                <div class="sms-warning-title">⚠ This permit has expired</div>
                <div class="sms-warning-sub">Consider notifying the applicant that renewal is required.</div>
            </div>
        </div>
        @elseif($isExpiringSoon)
        <div class="sms-warning">
            <div class="sms-warning-icon">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="sms-warning-title">⏳ Expiring in {{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }}</div>
                <div class="sms-warning-sub">Send an expiry warning SMS to remind the applicant to renew.</div>
            </div>
        </div>
        @endif

        {{-- Contact info --}}
        @if(!$canSend)
        <div class="sms-no-contact">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            No contact number on record. Edit the permit to add a contact before sending SMS.
        </div>
        @else
        <div style="font-size:12px;color:#374151">
            <span style="font-weight:600;color:#1a2744">📱 {{ $contact }}</span>
            <span style="color:#9ca3af;margin-left:.4rem">— {{ $permit->applicant_name }}</span>
        </div>
        @endif

        {{-- Quick send buttons --}}
        <div>
            <div style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:.5rem">Quick Send</div>
            <div class="sms-quick">

                {{-- Expiry Warning --}}
                <form method="POST" action="{{ route('sms.send', $permit) }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="message_type" value="expiry_warning">
                    <button type="submit" class="btn-sms-quick btn-sms-amber" {{ !$canSend ? 'disabled' : '' }}>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Expiry Warning
                    </button>
                </form>

                {{-- Active / Issued --}}
                @if($permit->status === 'active')
                <form method="POST" action="{{ route('sms.send', $permit) }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="message_type" value="active">
                    <button type="submit" class="btn-sms-quick btn-sms-blue" {{ !$canSend ? 'disabled' : '' }}>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Permit Issued
                    </button>
                </form>
                @endif

                {{-- Custom --}}
                <button type="button" class="btn-sms-quick"
                    style="border-color:#e5e7eb;color:#374151;"
                    onclick="document.getElementById('smsCustomModal').classList.add('open')"
                    {{ !$canSend ? 'disabled' : '' }}>
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Custom Message
                </button>

            </div>
        </div>

        {{-- SMS Log --}}
        <div>
            <div style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:.5rem">Send History</div>
            <div class="sms-log">
                @php $logs = $permit->smsNotifications()->latest()->take(5)->get(); @endphp
                @if($logs->isEmpty())
                    <div class="sms-log-empty">No SMS sent yet for this permit.</div>
                @else
                <table>
                    <thead><tr><th>Type</th><th>Recipient</th><th>Sent</th><th>Status</th></tr></thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td style="font-weight:600">{{ ucfirst(str_replace('_',' ',$log->type)) }}</td>
                            <td>{{ $log->recipient_number }}</td>
                            <td style="color:#6b7280">{{ $log->sent_at ? $log->sent_at->format('M d, Y g:i A') : $log->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                @if($log->status === 'sent')
                                    <span class="badge-sms-sent">Sent</span>
                                @elseif($log->status === 'failed')
                                    <span class="badge-sms-failed">Failed</span>
                                @else
                                    <span class="badge-sms-dev">Dev</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

    </div>{{-- /sms-body --}}
</div>

{{-- Custom Message Modal --}}
<div class="sms-modal-overlay" id="smsCustomModal" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="sms-modal">
        <div class="sms-modal-head">
            <h3>✉️ Send Custom SMS</h3>
            <button class="sms-modal-close" onclick="document.getElementById('smsCustomModal').classList.remove('open')">×</button>
        </div>
        <form method="POST" action="{{ route('sms.send', $permit) }}">
            @csrf
            <input type="hidden" name="message_type" value="custom">
            <div class="sms-modal-body">
                <div style="font-size:13px;color:#374151">
                    Sending to: <strong>{{ $contact }}</strong> ({{ $permit->applicant_name }})
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px">
                        Message <span style="color:#ef4444">*</span>
                    </label>
                    <textarea name="custom_message" id="smsCustomText" rows="4" maxlength="160" required
                        placeholder="Type your message here…"
                        style="font-family:'Inter',sans-serif;font-size:13px;color:#111827;padding:.55rem .8rem;border:1px solid #d1d5db;border-radius:7px;outline:none;width:100%;resize:vertical;line-height:1.5"
                        oninput="updateSmsCount(this)"></textarea>
                    <div class="sms-char-count" id="smsCharCount">0 / 160</div>
                </div>
                <div style="font-size:11px;color:#9ca3af;background:#f9fafb;border-radius:6px;padding:.6rem .8rem">
                    💡 Keep messages under 160 characters for a single SMS. Longer messages may be split and billed as 2 SMS.
                </div>
            </div>
            <div class="sms-modal-foot">
                <button type="button" style="padding:.5rem 1rem;border-radius:6px;border:1px solid #e5e7eb;font-family:'Inter',sans-serif;font-size:13px;color:#374151;background:#fff;cursor:pointer" onclick="document.getElementById('smsCustomModal').classList.remove('open')">Cancel</button>
                <button type="submit" style="display:inline-flex;align-items:center;gap:5px;padding:.5rem 1rem;border-radius:6px;border:none;font-family:'Inter',sans-serif;font-size:13px;font-weight:500;color:#fff;background:#1a2744;cursor:pointer">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Send SMS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateSmsCount(el) {
    const count = el.value.length;
    const el2 = document.getElementById('smsCharCount');
    el2.textContent = count + ' / 160';
    el2.className = 'sms-char-count' + (count > 140 ? ' warn' : '');
}
</script>