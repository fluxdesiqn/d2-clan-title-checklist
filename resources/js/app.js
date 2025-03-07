import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function updateForm() {
    const activityType = document.getElementById('activityType').value;
    const activitySelect = document.getElementById('activity');
    const encounterSelect = document.getElementById('encounter');
    const guardianInputs = document.querySelectorAll('.guardian-input');

    // Clear current options
    activitySelect.innerHTML = '';
    encounterSelect.innerHTML = '';

    if (activityType === 'raid') {
        raids.forEach(raidName => {
            const option = document.createElement('option');
            option.value = raidName;
            option.textContent = raidName;
            activitySelect.appendChild(option);
        });

        for (let i = 1; i <= 6; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = 'Encounter ' + i;
            encounterSelect.appendChild(option);
        }

        guardianInputs.forEach((input, index) => {
            input.style.display = index < 6 ? 'flex' : 'none';
        });
    } else if (activityType === 'dungeon') {
        dungeons.forEach(dungeonName => {
            const option = document.createElement('option');
            option.value = dungeonName;
            option.textContent = dungeonName;
            activitySelect.appendChild(option);
        });

        for (let i = 1; i <= 3; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = 'Encounter ' + i;
            encounterSelect.appendChild(option);
        }

        guardianInputs.forEach((input, index) => {
            input.style.display = index < 3 ? 'flex' : 'none';
        });
    }
}

// Initialize form on page load
document.addEventListener('DOMContentLoaded', updateForm);