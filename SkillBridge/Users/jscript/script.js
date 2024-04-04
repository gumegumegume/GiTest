        function confirmRegister() {
            return confirm("Are you sure you want to register?");
        }

        function searchUsers(searchTerm) {
            var jobPostings = document.querySelectorAll('.jobPosting');
            for (var i = 0; i < jobPostings.length; i++) {
                var posting = jobPostings[i];
                var title = posting.querySelector('h4').textContent.toLowerCase();
                if (title.includes(searchTerm.toLowerCase())) {
                    posting.style.display = 'block';
                } else {
                    posting.style.display = 'none';
                }
            }
        }
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('navbar');
            if (window.scrollY > 0) {
              navbar.classList.add('scrolled');
            } else {
              navbar.classList.remove('scrolled');
            }
          });


        

        
        
