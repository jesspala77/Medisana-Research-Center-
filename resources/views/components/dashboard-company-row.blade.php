<div class="company-cell">
    <div class="company-avatar">{{ $company['avatar'] }}</div>
    <div>
        <div class="company-name">{{ $company['name'] }}</div>
        <div class="company-division">{{ $company['division'] }}</div>
    </div>
</div>

<div>
    <span class="status-pill status-{{ $statusClass }}">{{ $company['status'] }}</span>
    <div class="cell-value" style="margin-top: 5px;">{{ $company['revenue'] }}</div>
    <div class="positive">▲ {{ $company['revenue_delta'] }}</div>
</div>

<div>
    <div class="cell-value">{{ $company['tasks'] }}</div>
    <div class="cell-detail">{{ $company['task_status'] }}</div>
</div>

<div>
    <div class="cell-value">{{ $company['pipeline'] }}</div>
    <div class="cell-detail">{{ $company['pipeline_status'] }}</div>
</div>

<div class="health-score health-{{ $statusClass }}" style="--score: {{ $company['health'] }}%;">
    <span>{{ $company['health'] }}%</span>
</div>
