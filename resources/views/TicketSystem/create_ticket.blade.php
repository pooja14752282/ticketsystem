@extends('layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/create-ticket.css') }}">
@endsection

@section('content')

<div class="create-ticket-container">

    <h2 class="page-title">🎫 Create New Ticket</h2>

    <form method="POST"
          action="{{ route('ticketsystem.store') }}"
          enctype="multipart/form-data">

        @csrf

        {{-- Description --}}
        <div class="form-group">
            <label>Description *</label>

            <textarea name="description"
                      rows="4"
                      required>{{ old('description') }}</textarea>

            @error('description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        {{-- Category --}}
        <div class="form-group">
            <label>Category *</label>

            <input type="text"
                   name="category"
                   value="{{ old('category') }}"
                   required>

            @error('category')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        {{-- Priority --}}
        <div class="form-group">
            <label>Priority *</label>

            <select name="priority"
                    id="priority-select"
                    required>

                <option value="">-- Select Priority --</option>

                @foreach($priorities as $p)
                    <option value="{{ $p->value }}"
                            data-days="{{ ['low'=>5,'high'=>3,'urgent'=>2][$p->value] ?? 3 }}"
                            data-color="{{ $p->color }}"
                            data-text="{{ $p->text_color }}"
                            {{ old('priority') == $p->value ? 'selected' : '' }}>
                        {{ $p->label }}
                    </option>
                @endforeach
            </select>

            @error('priority')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        {{-- Due Date --}}
        <div class="form-group">

            <label>
                Due Date
                <span class="label-light">
                    — auto-set by priority
                </span>
            </label>

            <div class="due-date-row">

                <input type="date"
                       name="due_date"
                       id="due-date-input"
                       value="{{ old('due_date') }}">

                <span id="due-date-badge"
                      class="due-date-badge">
                </span>

            </div>

            <p class="helper-text">
                Low = +5 days |
                High = +3 days |
                Urgent = +2 days
            </p>

        </div>

        {{-- Assign To --}}
        <div class="form-group">

            <label>Assign To</label>

            <select name="assigned_to">

                <option value="">-- Select --</option>

                @if($admins->count())
                    <optgroup label="👑 Admin">

                        @foreach($admins as $admin)

                            <option value="user_{{ $admin->id }}">
                                {{ $admin->name }} (Admin)
                            </option>

                        @endforeach

                    </optgroup>
                @endif

               @if($supportMembers->count())
                   <optgroup label="👥 Support Team">
                   @foreach($supportMembers as $member)
                   <option value="team_{{ $member->id }}">
                   {{ $member->name }} — {{ \App\Models\TicketSupportTeam::APPS[$member->app_assigned] ?? $member->app_assigned }}
                   </option>
                @endforeach
                   </optgroup>
                 @endif

            </select>

        </div>

        {{-- Attachment --}}
        <div class="form-group">

            <label>
                Attach File
                <span class="label-light">(optional)</span>
            </label>

            <input type="file"
                   name="attachment"
                   accept=".png,.jpg,.jpeg,.pdf,.doc,.docx">

            <p class="helper-text">
                PNG, JPG, PDF, DOC up to 10MB
            </p>

            @error('attachment')
                <span class="error-text">{{ $message }}</span>
            @enderror

        </div>

        <div class="button-row">

            <button type="submit"
                    class="btn-submit">
                Submit Ticket
            </button>

            <a href="{{ route('ticketsystem.my') }}"
               class="btn-back">
                Back
            </a>

        </div>

    </form>

</div>

@endsection


@section('scripts')
<script src="{{ asset('js/create-ticket.js') }}"></script>
@endsection