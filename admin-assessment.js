let questionCount = 0;
let resultsCount = 0;
const existingQuestions = window.AssessmentData || [];
const existingResults = window.ResultData || [];

window.onload = function () {
    if (existingQuestions.length > 0) {
        existingQuestions.forEach(q => addQuestion(q));
    } else {
        addQuestion();
    }
    if (existingResults.length > 0) {
        existingResults.forEach(r => addResultRow(r));
    } else {
        addResultRow();
    }
};

function deleteFromDatabase(id, type) {
    if (!id) {
        return Promise.resolve(); 
    }

    const url = 'delete_item.php'; 
    const data = new URLSearchParams();
    data.append('id', id);
    data.append('type', type); 

    return fetch(url, {
        method: 'POST',
        body: data
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log(`${type} with ID ${id} deleted successfully from DB.`);
        } else {
            alert(`Error deleting ${type}: ${data.message || 'Unknown error'}`);
            throw new Error(data.message || 'Database deletion failed');
        }
    })
    .catch(error => {
        console.error('AJAX deletion error:', error);
        alert(`Failed to delete ${type} from the database. Please try again.`);
        throw error; 
    });
}


function addQuestion(existing = null) {
    questionCount++;

    let qBox = document.createElement("div");
    qBox.className = "question-box";

    const questionId = existing ? existing.question_id : ''; 
    qBox.innerHTML = `
    <div class="question-header">
        <h4>Q${questionCount}</h4>
        <button type="button" class="delete-question-btn" onclick="deleteQuestion(this, '${questionId}')">✖</button>
    </div>

    <input type="hidden" name="question_ids[]" value="${questionId}">

    <input type="text" name="questions[]" placeholder="Question text"
    value="${existing ? existing.question_text : ''}" required>

    <div class="options">
        <label>Options :</label>
        <button type="button" class="add-option-btn" onclick="addOption(this)">+ Add Option</button>
    </div>
    `;

    document.getElementById("questions-container").appendChild(qBox);

    if (existing && existing.options && existing.options.length > 0) {
        existing.options.forEach(opt => addOption(qBox.querySelector(".add-option-btn"), opt));
    } else if (!existing) {
        let addButton = qBox.querySelector(".add-option-btn");
        addOption(addButton);
        addOption(addButton);
    }
}
function addOption(button, existing = null) {
    let optionRow = document.createElement("div");
    optionRow.className = "option-row";

    const optionId = existing ? existing.option_id : '';

    optionRow.innerHTML = `
        <input type="hidden" name="option_ids[]" value="${optionId}">
        
        <input type="text" name="options_text[]" placeholder="Option text"
            value="${existing ? existing.option_text : ''}" required>

        <button type="button" class="delete-option-btn"
            onclick="deleteOption(this, '${optionId}')">✖</button>
    `;

    button.parentElement.appendChild(optionRow);
}



function deleteQuestion(button, questionId) {
    if (confirm("Delete this question?")) {
        deleteFromDatabase(questionId, 'question')
            .then(() => {
                button.closest(".question-box").remove();
            })
            .catch(() => {
            });
    }
}
function deleteOption(button, optionId) {
    if (confirm("Delete this option?")) {

        deleteFromDatabase(optionId, 'option')
            .then(() => {
                button.closest(".option-row").remove();
            })
            .catch(() => {});
    }
}



// --- Result Functions ---

function addResultRow(existing = null) {
    resultsCount++;

    let rBox = document.createElement("div");
    rBox.className = "result-row";
    
    // Extract the result ID for deletion
    const resultId = existing ? existing.result_id : ''; 

    rBox.innerHTML = `
    <input type="hidden" name="result_ids[]" value="${resultId}">

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

    <button type="button" class="delete-result-btn" onclick="deleteResultRow(this, '${resultId}')">Delete Range</button>
    <hr style="border-top: 1px solid #eee;">
`;

    document.getElementById("results-container").appendChild(rBox);
}

function deleteResultRow(button, resultId) {
    if (confirm("Delete this result range?")) {
        // 1. Try to delete from the database if an ID exists
        deleteFromDatabase(resultId, 'result')
            .then(() => {
                // 2. If successful (or no ID exists), remove from the DOM
                button.closest(".result-row").remove();
            })
            .catch(() => {
                // If deletion fails, do nothing
            });
    }
}