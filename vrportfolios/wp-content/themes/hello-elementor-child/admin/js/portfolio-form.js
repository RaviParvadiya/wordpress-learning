/* document.getElementById('add-skill').addEventListener('click', function() {
    const wrapper = document.getElementById('skills-wrapper');
    const index = wrapper.children.length;
    const div = document.createElement('div');
    div.className = 'skill-item';
    div.innerHTML = `
    <input type="text" name="portfolio_skills[${index}][title]" placeholder="Skill title" />
`;
    wrapper.appendChild(div);
}); */

document
  .getElementById("add-other-skill")
  .addEventListener("click", function () {
    const wrapper = document.getElementById("other-skills-wrapper");
    const index = wrapper.children.length;
    const div = document.createElement("div");
    div.className = "other-skill-item";
    div.innerHTML = `
                <input type="text" name="other_skills[${index}][title]" placeholder="Skill name (required)" required />
                <input type="text" name="other_skills[${index}][link]" placeholder="Skill image URL (optional)" />
                <button type="button" class="remove-other-skill">Remove</button>
            `;
    wrapper.appendChild(div);
  });

document
  .getElementById("other-skills-wrapper")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-other-skill")) {
      e.target.parentElement.remove();
    }
  });

document.getElementById("add-project").addEventListener("click", function () {
  const wrapper = document.getElementById("projects-wrapper");
  const index = wrapper.children.length;
  const div = document.createElement("div");
  div.className = "project-item";
  div.innerHTML = `
    <input type="text" name="portfolio_projects[${index}][subtitle]" placeholder="Project subtitle" required />
    <input type="text" name="portfolio_projects[${index}][title]" placeholder="Project title" required />
    <br>
    <textarea name="portfolio_projects[${index}][description]" rows="5" cols="60" required ></textarea>
    <label for="portfolio_projects_screenshot[${index}]">Project Image:</label>
    <input type="file" name="portfolio_projects_screenshot[${index}]" id="portfolio_projects_screenshot[${index}]" accept="image/*">
    <br>
    <button type="button" class="remove-other-skill">Remove</button>
    `;
  wrapper.appendChild(div);
});

document
  .getElementById("projects-wrapper")
  .addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-other-skill")) {
      e.target.parentElement.remove();
    }
  });
