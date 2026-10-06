@extends('layouts.app')

@section('title', 'Create Project')

@section('page-title', 'Create Project')

@section('content')

    <section class="page-header">
        <div class="page-header-copy">
            <h2>Register a New Project</h2>

            <p>
                Enter the project information below. After saving
                the project, you will be able to enter assessment
                outputs, risks and upload supporting documents.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('projects.index') }}"
                class="button button-secondary"
            >
                ← Back to Projects
            </a>
        </div>
    </section>

    <form
        method="POST"
        action="{{ route('projects.store') }}"
    >
        @csrf

        <div style="display: grid; gap: 22px;">

            {{-- Basic information --}}
            <section class="panel">
                <div class="panel-header">
                    <div>
                        <h2>1. Basic Project Information</h2>
                    </div>

                    <span class="status-badge status-warning">
                        Required Information
                    </span>
                </div>

                <div class="panel-body">
                    <div class="form-grid">

                        <div class="form-group">
                            <label
                                for="project_code"
                                class="form-label"
                            >
                                Project Code *
                            </label>

                            <input
                                id="project_code"
                                name="project_code"
                                type="text"
                                class="form-control"
                                value="{{ old('project_code') }}"
                                placeholder="Example: DOST-2026-001"
                                maxlength="100"
                                required
                            >

                            <span class="form-help">
                                Enter a unique code for this project.
                            </span>

                            @error('project_code')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="title"
                                class="form-label"
                            >
                                Project Title *
                            </label>

                            <input
                                id="title"
                                name="title"
                                type="text"
                                class="form-control"
                                value="{{ old('title') }}"
                                placeholder="Enter the project title"
                                maxlength="255"
                                required
                            >

                            @error('title')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label
                                for="description"
                                class="form-label"
                            >
                                Project Description
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                class="form-control"
                                placeholder="Describe the project objectives, activities and expected impact."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="implementing_agency"
                                class="form-label"
                            >
                                Implementing Agency
                            </label>

                            <input
                                id="implementing_agency"
                                name="implementing_agency"
                                type="text"
                                class="form-control"
                                value="{{
                                    old('implementing_agency')
                                }}"
                                placeholder="Agency or organisation"
                                maxlength="255"
                            >

                            @error('implementing_agency')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="project_leader"
                                class="form-label"
                            >
                                Project Leader
                            </label>

                            <input
                                id="project_leader"
                                name="project_leader"
                                type="text"
                                class="form-control"
                                value="{{ old('project_leader') }}"
                                placeholder="Full name"
                                maxlength="255"
                            >

                            @error('project_leader')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="sector"
                                class="form-label"
                            >
                                Sector
                            </label>

                            <input
                                id="sector"
                                name="sector"
                                type="text"
                                class="form-control"
                                value="{{ old('sector') }}"
                                placeholder="Example: Agriculture"
                                maxlength="255"
                            >

                            @error('sector')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="status"
                                class="form-label"
                            >
                                Project Status *
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-control"
                                required
                            >
                                <option
                                    value="draft"
                                    @selected(
                                        old(
                                            'status',
                                            'draft'
                                        ) === 'draft'
                                    )
                                >
                                    Draft
                                </option>

                                <option
                                    value="in_progress"
                                    @selected(
                                        old('status')
                                        === 'in_progress'
                                    )
                                >
                                    In Progress
                                </option>

                                <option
                                    value="completed"
                                    @selected(
                                        old('status')
                                        === 'completed'
                                    )
                                >
                                    Completed
                                </option>
                            </select>

                            @error('status')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Schedule and financial information --}}
            <section class="panel">
                <div class="panel-header">
                    <div>
                        <h2>2. Schedule and Financial Information</h2>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-grid">

                        <div class="form-group">
                            <label
                                for="start_date"
                                class="form-label"
                            >
                                Start Date *
                            </label>

                            <input
                                id="start_date"
                                name="start_date"
                                type="date"
                                class="form-control"
                                value="{{ old('start_date') }}"
                                required
                            >

                            @error('start_date')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="completion_date"
                                class="form-label"
                            >
                                Completion Date
                            </label>

                            <input
                                id="completion_date"
                                name="completion_date"
                                type="date"
                                class="form-control"
                                value="{{
                                    old('completion_date')
                                }}"
                            >

                            @error('completion_date')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="approved_budget"
                                class="form-label"
                            >
                                Approved Budget (₱)
                            </label>

                            <input
                                id="approved_budget"
                                name="approved_budget"
                                type="number"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{
                                    old('approved_budget')
                                }}"
                                placeholder="0.00"
                            >

                            @error('approved_budget')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="actual_expenditure"
                                class="form-label"
                            >
                                Actual Expenditure (₱)
                            </label>

                            <input
                                id="actual_expenditure"
                                name="actual_expenditure"
                                type="number"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="{{
                                    old('actual_expenditure')
                                }}"
                                placeholder="0.00"
                            >

                            @error('actual_expenditure')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Assessment baseline --}}
            <section class="panel">
                <div class="panel-header">
                    <div>
                        <h2>3. Assessment Baseline</h2>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="form-grid">

                        <div class="form-group">
                            <label
                                for="total_target_objectives"
                                class="form-label"
                            >
                                Total Target Objectives *
                            </label>

                            <input
                                id="total_target_objectives"
                                name="total_target_objectives"
                                type="number"
                                class="form-control"
                                min="1"
                                value="{{
                                    old(
                                        'total_target_objectives',
                                        1
                                    )
                                }}"
                                required
                            >

                            <span class="form-help">
                                Number of planned project objectives.
                            </span>

                            @error('total_target_objectives')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label
                                for="total_accomplishments"
                                class="form-label"
                            >
                                Total Accomplishments *
                            </label>

                            <input
                                id="total_accomplishments"
                                name="total_accomplishments"
                                type="number"
                                class="form-control"
                                min="0"
                                value="{{
                                    old(
                                        'total_accomplishments',
                                        0
                                    )
                                }}"
                                required
                            >

                            <span class="form-help">
                                Number of objectives already achieved.
                            </span>

                            @error('total_accomplishments')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label
                                for="primary_output_category"
                                class="form-label"
                            >
                                Primary Output Category
                            </label>

                            <select
                                id="primary_output_category"
                                name="primary_output_category"
                                class="form-control"
                            >
                                <option value="">
                                    Select a category
                                </option>

                                <option
                                    value="publication"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'publication'
                                    )
                                >
                                    Publication
                                </option>

                                <option
                                    value="people_services"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'people_services'
                                    )
                                >
                                    People Services
                                </option>

                                <option
                                    value="partnership"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'partnership'
                                    )
                                >
                                    Partnership
                                </option>

                                <option
                                    value="product"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'product'
                                    )
                                >
                                    Product
                                </option>

                                <option
                                    value="patent"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'patent'
                                    )
                                >
                                    Patent
                                </option>

                                <option
                                    value="policy"
                                    @selected(
                                        old(
                                            'primary_output_category'
                                        ) === 'policy'
                                    )
                                >
                                    Policy
                                </option>
                            </select>

                            <span class="form-help">
                                This category is used by the impact
                                assessment calculation.
                            </span>

                            @error('primary_output_category')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Form actions --}}
            <section class="panel">
                <div class="panel-body">
                    <div class="page-actions">
                        <button
                            type="submit"
                            class="button button-primary"
                        >
                            Save Project
                        </button>

                        <a
                            href="{{ route('projects.index') }}"
                            class="button button-secondary"
                        >
                            Cancel
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </form>

@endsection
