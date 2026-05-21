import Chart from 'chart.js/auto';

// Store Chart instances for reuse
const chartInstances = {};

export function initAttendanceChart(canvasId, labels, presentData, absentData) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    // Destroy existing chart on same canvas
    if (chartInstances[canvasId]) {
        chartInstances[canvasId].destroy();
    }

    chartInstances[canvasId] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Present',
                    data: presentData,
                    borderColor: '#0F1E38',
                    backgroundColor: 'rgba(15,30,56,0.06)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2,
                    pointBackgroundColor: '#0F1E38'
                },
                {
                    label: 'Absent',
                    data: absentData,
                    borderColor: '#DC2626',
                    backgroundColor: 'rgba(220,38,38,0.05)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    borderWidth: 2,
                    borderDash: [4, 3],
                    pointBackgroundColor: '#DC2626'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 10 }, color: '#94A3B8' }
                },
                y: {
                    grid: { color: 'rgba(15,30,56,0.04)' },
                    border: { display: false },
                    ticks: { font: { size: 10 }, color: '#94A3B8' },
                    min: 0
                }
            }
        }
    });

    return chartInstances[canvasId];
}

// Live Clock Update
export function initLiveClock(clockElId) {
    const clockEl = document.getElementById(clockElId);
    if (!clockEl) return;

    function update() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        clockEl.textContent = `${h}:${m}:${s}`;
    }

    update();
    setInterval(update, 1000);
}

// Auto-init if elements exist on page
document.addEventListener('DOMContentLoaded', () => {
    // Note: live-clock init removed to avoid duplicate ID conflicts
    // Each page should init its own clock if needed

    const attendCanvas = document.getElementById('attendChart');
    if (attendCanvas && window.__chartData) {
        initAttendanceChart(
            'attendChart',
            window.__chartData.labels,
            window.__chartData.present,
            window.__chartData.absent
        );
    }
});