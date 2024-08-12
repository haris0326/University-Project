document.getElementById('degree').addEventListener('change', function() {
    const subjectsSelect = document.getElementById('subjects');
    subjectsSelect.innerHTML = ''; // Clear previous options

    const subjects = {
        'CS': [
            'Data Structures and Algorithms',
            'Operating Systems',
            'Computer Networks',
            'Database Systems',
            'Software Engineering',
            'Artificial Intelligence',
            'Cybersecurity',
            'Web Development'
        ],
        'Eng': [
            'Mechanics',
            'Thermodynamics',
            'Electrical Circuits',
            'Structural Analysis',
            'Fluid Dynamics',
            'Materials Science',
            'Control Systems',
            'Engineering Design and Innovation'
        ],
        'BBA': [
            'Accounting',
            'Economics',
            'Marketing Management',
            'Financial Management',
            'Organizational Behavior',
            'Business Law',
            'Operations Management',
            'Strategic Management'
        ],
        'HC': [
            'Anatomy and Physiology',
            'Pharmacology',
            'Medical Ethics',
            'Healthcare Management',
            'Epidemiology',
            'Patient Care and Clinical Skills',
            'Healthcare Informatics',
            'Public Health Policy and Administration'
        ]
    };

    const selectedDegree = this.value;
    if (subjects[selectedDegree]) {
        subjects[selectedDegree].forEach(subject => {
            const option = document.createElement('option');
            option.value = subject;
            option.text = subject;
            subjectsSelect.appendChild(option);
        });
    }
});