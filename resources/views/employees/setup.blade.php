<x-app-layout title="Complete Employee Profile" crumb="Admin · Requests · Profile Setup">

<div class="page-header">
    <div>
        <div class="page-header-title">Complete Employee Profile</div>
        <div class="page-header-sub">
            @if($newUser)
                Filling in profile for <strong>{{ $newUser->name }}</strong> ({{ $newUser->email }}) — account has been activated.
            @else
                Fill in the employee profile details below.
            @endif
        </div>
    </div>
    <a href="{{ route('requests.index') }}" class="btn-secondary">← Back to Requests</a>
</div>

{{-- Progress Tracker --}}
<div id="progress-container" style="margin-bottom: 20px;"></div>

{{-- Activation success banner --}}
@if(session('success'))
<div style="display:flex;align-items:center;gap:10px;background:#DCFCE7;border:1px solid rgba(22,163,74,0.2);border-radius:10px;padding:14px 18px;margin-bottom:20px;font-size:13px;color:#166534">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    <div>
        <strong>Account approved!</strong> {{ session('success') }}
        Now please complete the employee profile below so the new user can access the system.
    </div>
</div>
@endif

<div class="form-card" style="max-width:720px">
    <form method="POST" action="{{ route('employees.setup.store') }}" id="employee-setup-form" data-validate>>
        @csrf

        {{-- Hidden user_id to link employee record to the user account --}}
        @if($newUser)
        <input type="hidden" name="user_id" value="{{ $newUser->id }}">
        @endif

        {{-- Pre-fill email from user account --}}
        @php $prefillEmail = $newUser?->email ?? old('email'); @endphp
        @php $prefillName  = $newUser ? explode(' ', $newUser->name, 2) : [old('first_name'), old('last_name')]; @endphp

        {{-- SECTION 1: PERSONAL --}}
        <div class="form-title">Personal information</div>
        <div class="form-row">
            <div class="form-group">
                <label>First name *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $prefillName[0] ?? '') }}" required class="{{ $errors->has('first_name') ? 'input-error' : '' }}">
                @error('first_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Last name *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $prefillName[1] ?? '') }}" required class="{{ $errors->has('last_name') ? 'input-error' : '' }}">
                @error('last_name')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email address *</label>
                <input type="email" name="email" value="{{ old('email', $prefillEmail) }}" required class="{{ $errors->has('email') ? 'input-error' : '' }}" {{ $newUser ? 'readonly style=opacity:.6' : '' }}>
                @if($newUser)<div style="font-size:10px;color:var(--text3);margin-top:3px">Pre-filled from registration — edit if needed.</div>@endif
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Phone number</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date of birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>
            <div class="form-group">
                <label>Home address</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="City, Province">
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 2: EMPLOYMENT --}}
        <div class="form-title">Employment details</div>
        <div class="form-row">
            <div class="form-group">
                <label>Department *</label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="e.g. Engineering" required class="{{ $errors->has('department') ? 'input-error' : '' }}">
                @error('department')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Position / Job title *</label>
                <input type="text" name="position" value="{{ old('position') }}" placeholder="e.g. Software Engineer" required class="{{ $errors->has('position') ? 'input-error' : '' }}">
                @error('position')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Date hired *</label>
                <input type="date" name="date_hired" value="{{ old('date_hired', now()->format('Y-m-d')) }}" required class="{{ $errors->has('date_hired') ? 'input-error' : '' }}">
                @error('date_hired')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Employment status *</label>
                <select name="status" required id="empStatus" onchange="toggleContractExpiry(this.value)">
                    @foreach(['active' => 'Active (Regular)', 'probationary' => 'Probationary', 'contractual' => 'Contractual', 'inactive' => 'Inactive'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row" id="contractExpiryRow" style="{{ old('status') === 'contractual' ? '' : 'display:none' }}">
            <div class="form-group">
                <label id="contractExpiryLabel">Contract expiry date</label>
                <input type="date" name="contract_expiry" value="{{ old('contract_expiry') }}" id="contractExpiryInput" class="{{ $errors->has('contract_expiry') ? 'input-error' : '' }}">
                <div style="font-size:10px;color:var(--text3);margin-top:3px">Required for contractual employees — alerts will trigger 30 days before.</div>
                @error('contract_expiry')<div class="error-msg">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="divider"></div>

        {{-- SECTION 3: GOVERNMENT IDs --}}
        <div class="form-title">Government IDs</div>
        <div style="font-size:12px;color:var(--text3);margin-bottom:14px;line-height:1.6">
            Required for SSS, Pag-IBIG, and PhilHealth contributions. These can be filled in later via the employee profile edit page if not available now.
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>SSS number</label>
                <input type="text" name="sss_number" value="{{ old('sss_number') }}" placeholder="00-0000000-0" title="Format: 00-0000000-0">
            </div>
            <div class="form-group">
                <label>Pag-IBIG (HDMF) number</label>
                <input type="text" name="pagibig_number" value="{{ old('pagibig_number') }}" placeholder="0000-0000-0000" title="Format: 0000-0000-0000">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>PhilHealth number</label>
                <input type="text" name="philhealth_number" value="{{ old('philhealth_number') }}" placeholder="00-000000000-0" title="Format: 00-000000000-0">
            </div>
        </div>

        <div class="divider"></div>

        <div class="form-actions">
            <button type="submit" class="btn-primary" style="padding:10px 24px" id="submitBtn" onclick="setLoading(event)">
                <span id="submitText">Save employee profile</span>
                <span id="submitLoader" style="display:none; width: 16px; height: 16px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.8s linear infinite;"></span>
            </button>
            <a href="{{ route('dashboard') }}" class="btn-secondary">Skip for now</a>
            <span style="font-size:11px;color:var(--text3);margin-left:auto">
                Fields marked * are required. Government IDs can be added later.
            </span>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Initialize progress tracker
document.addEventListener('DOMContentLoaded', () => {
  const progressTracker = new ProgressTracker({
    steps: [
      { label: 'Personal Info', completed: false },
      { label: 'Employment', completed: false },
      { label: 'IDs & Docs', completed: false },
    ],
    container: document.getElementById('progress-container'),
    onStepChange: (step) => {
      const sections = document.querySelectorAll('.form-title');
      if (sections[step]) {
        sections[step].scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }
  });

  const form = document.getElementById('employee-setup-form');
  if (form) {
    const personalSection = form.querySelector('input[name="first_name"]');
    const employmentSection = form.querySelector('input[name="department"]');
    const idsSection = form.querySelector('input[name="sss_number"]');

    const checkSectionCompletion = () => {
      if (personalSection?.value && form.querySelector('input[name="last_name"]')?.value &&
          form.querySelector('input[name="email"]')?.value) {
        progressTracker.steps[0].completed = true;
      }

      if (employmentSection?.value && form.querySelector('input[name="position"]')?.value &&
          form.querySelector('input[name="date_hired"]')?.value) {
        progressTracker.steps[1].completed = true;
      }

      if (idsSection?.value) {
        progressTracker.steps[2].completed = true;
      }

      progressTracker.render();
    };

    form.addEventListener('input', checkSectionCompletion);
    form.addEventListener('change', checkSectionCompletion);
    checkSectionCompletion();

    let formDirty = false;

    form.addEventListener('input', function() {
        formDirty = true;
    });

    form.addEventListener('change', function() {
        formDirty = true;
    });

    form.addEventListener('submit', (e) => {
      formDirty = false;
      LoadingOverlay.show('Saving employee profile...');
    });

    window.addEventListener('beforeunload', function(e) {
        if (formDirty) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });
  }
});

function toggleContractExpiry(val) {
    const row = document.getElementById('contractExpiryRow');
    const input = document.getElementById('contractExpiryInput');
    const label = document.getElementById('contractExpiryLabel');

    if (val === 'contractual') {
        row.style.display = '';
        input.required = true;
        label.innerHTML = 'Contract expiry date *';
    } else {
        row.style.display = 'none';
        input.required = false;
        input.value = '';
        label.innerHTML = 'Contract expiry date';
    }
}

function setLoading(event) {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    document.getElementById('submitText').style.display = 'none';
    document.getElementById('submitLoader').style.display = 'inline-block';
}
</script>
@endpush

</x-app-layout>
