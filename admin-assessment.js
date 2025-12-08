 let questionCount = 0;
let resultsCount = 0;

// Access global data variables created by the PHP script
const existingQuestions = window.AssessmentData || [];
const existingResults = window.ResultData || [];


window.onload = function () {
    // Check if data exists and load questions/options
    if (existingQuestions.length > 0) {
        existingQuestions.forEach(q => addQuestion(q));
    } else {
        // If no existing data, add one blank question to start
        addQuestion();
    }
    
    // Check if data exists and load result rows
    if (existingResults.length > 0) {
        existingResults.forEach(r => addResultRow(r));
    } else {
        // If no existing data, add one blank result row to start
        addResultRow();
    }
};


// --- Question and Option Functions ---

function addQuestion(existing = null) {
    questionCount++;

    let qBox = document.createElement("div");
    qBox.className = "question-box";

    // Use HTML template literal for structure
    qBox.innerHTML = `
    <div class="question-header">
        <h4>Q${questionCount}</h4>
        <button type="button" class="delete-question-btn" onclick="deleteQuestion(this)">✖</button>
    </div>

    <input type="hidden" name="question_ids[]" value="${existing ? existing.question_id : ''}">

    <input type="text" name="questions[]" placeholder="Question text"
    value="${existing ? existing.question_text : ''}" required>

    <div class="options">
        <label>Options :</label>
        <button type="button" class="add-option-btn" onclick="addOption(this)">+ Add Option</button>
    </div>
    `;

    document.getElementById("questions-container").appendChild(qBox);

    // If editing, load existing options
    if (existing && existing.options && existing.options.length > 0) {
        existing.options.forEach(opt => addOption(qBox.querySelector(".add-option-btn"), opt));
    } else if (!existing) {
        // If adding a new blank question, add two blank options by default
        let addButton = qBox.querySelector(".add-option-btn");
        addOption(addButton);
        addOption(addButton);
    }
}


function addOption(button, existing = null) {
    let parent = button.parentElement;

    let row = document.createElement("div");
    row.className = "option-row";
    row.innerHTML = `
    <input type="hidden" name="option_ids[]" value="${existing ? existing.option_id : ''}">
    
    <input type="text" name="option_texts[]" placeholder="Option text"
             value="${existing ? existing.option_text : ''}" required>

    <input type="number" name="scores[]" placeholder="Score"
             value="${existing ? existing.score : ''}" required>

    <button type="button" class="delete-option-btn" onclick="deleteOption(this)">✖</button>
`;


    parent.appendChild(row);
}

function deleteQuestion(button) {
    if (confirm("Delete this question?")) {
        button.closest(".question-box").remove();
        // NOTE: For a complex app, you'd re-index questionCount here.
    }
}

function deleteOption(button) {
    if (confirm("Delete this option?")) {
        button.closest(".option-row").remove();
    }
}



function addResultRow(existing = null) {
    resultsCount++;

    let rBox = document.createElement("div");
    rBox.className = "result-row";

    rBox.innerHTML = `
    <input type="hidden" name="result_ids[]" value="${existing ? existing.result_id : ''}">

    <div class="score-range-inputs">
        <label>Score Range:</label>
        <input type="number" name="min_scores[]" placeholder="Min Score" style="width: 80px;"
            value="${existing ? existing.min_score : ''}" required>
        -
        <input type="number" name="max_scores[]" placeholder="Max Score" style="width: 80px;"
            value="${existing ? existing.max_score : ''}" required>
    </div>

    <label>Interpretation (e.g., 'Severe'):</label>
    <input type="text" name="interpretations[]" placeholder="Interpretation"
        value="${existing ? existing.interpretation : ''}" required>

    <label>Suggestion/Recommendation:</label>
    <textarea name="suggestions[]" rows="2" placeholder="Suggestion for this score range..."
        required>${existing ? existing.suggestion : ''}</textarea>

    <button type="button" class="delete-result-btn" onclick="deleteResultRow(this)">Delete Range</button>
    <hr style="border-top: 1px solid #eee;">
`;

    document.getElementById("results-container").appendChild(rBox);
}

function deleteResultRow(button) {
    if (confirm("Delete this result range?")) {
        button.closest(".result-row").remove();
    }
}