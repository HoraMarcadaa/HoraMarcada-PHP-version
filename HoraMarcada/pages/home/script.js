// Faz uma requisição para verificar a sessão
fetch('verificar_sessao.php')
  .then(response => response.json())
  .then(data => {
    if (data.logged_in) {
        // Exibe o nome do usuário e a imagem de perfil
        document.getElementById('username').textContent = data.user_name;
        document.getElementById('profile-picture').src = 'path/to/profile_pictures/' + data.user_id + '.jpg';
    } else {
        // Redireciona para a página de login se não estiver logado
        window.location.href = 'login.html';
    }
  })
  .catch(error => console.error('Erro:', error));
  
function toggleMenu() {
    const menu = document.getElementById('dropdown-menu');
    menu.classList.toggle('visible');
}



