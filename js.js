<script>
  const questions = document.querySelectorAll('.faq-question');
  questions.forEach = {
    q.addEventListener('click', () => {
      const answer = q.nextElementSibling;
      answer.classList.toggle('show');
    })
  };
</script>
