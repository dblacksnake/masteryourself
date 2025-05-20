<template>
    <div class="home-page">
      <!-- Header -->
      <header>
        <div class="logo">Master Yourself</div>
        <nav>
          <a href="#" >Accueil</a>
          <a @click="goToSignup">Rejoindre!</a>
          <a href="#about" >À propos</a>
        </nav>
      </header>
  
      <!-- Hero Section -->
      <section class="hero">
        <h1>Éveille-toi à travers le Mouvement</h1>
        <h2>Un voyage transformateur de 6 mois pour te reconnecter à ton essence, ouvrir ton esprit et tisser des liens profonds.</h2>
        <p>
          Master Yourself transcende la danse. C’est une révolution intérieure, une invitation à te découvrir, à te connecter authentiquement avec toi-même et les autres, et à embrasser une compréhension plus profonde du monde. Ce n’est pas juste un programme, c’est une évolution de vie. Rejoins une communauté vibrante et engagée, limitée à 20 âmes déterminées. Inscris-toi avant le 1er août pour entamer cette métamorphose !
        </p>
        <button @click="goToSignup">Commence ton voyage</button>
  
        <!-- Pop-up pour l'enregistrement -->
        <div id="signupPopup" class="popup">
          <div class="popup-content">
            <span @click="closePopup" class="close">×</span>
            <h2>Inscription</h2>
            <form id="signupForm" enctype="multipart/form-data" @submit.prevent="submitForm">
              <label for="full_name">Nom complet * :</label>
              <input type="text" id="full_name" v-model="form.full_name" name="full_name" required placeholder="Ton nom complet">
  
              <label for="age">Âge * :</label>
              <input type="number" id="age" v-model.number="form.age" name="age" required placeholder="Ton âge" min="0">
  
              <label for="email">Email * :</label>
              <input type="email" id="email" v-model="form.email" name="email" required placeholder="Ton email">
  
              <label for="instagram_tag">Tag Instagram :</label>
              <input type="text" id="instagram_tag" v-model="form.instagram_tag" name="instagram_tag" placeholder="@toncompte">
  
              <label for="description">Description :</label>
              <textarea id="description" v-model="form.description" name="description" placeholder="Parle-nous de toi" rows="4"></textarea>
  
              <label for="photo">Photo (facultatif) :</label>
              <input type="file" id="photo" ref="photoInput" name="photo" accept="image/*" @change="handleFileUpload('photo')">
  
              <label for="cv">CV (facultatif) :</label>
              <input type="file" id="cv" ref="cvInput" name="cv" accept=".pdf,.doc,.docx" @change="handleFileUpload('cv')">
  
              <button type="submit" >S'inscrire</button>
            </form>
          </div>
        </div>
      </section>
  
    <!-- About Section -->
    <section class="about-section" id="about">
      <h2>À Propos</h2>
      <p><strong>Mission :</strong> Créer un espace où la danse devient un outil de transformation personnelle, en connectant des passionnés de mouvement et en les aidant à se découvrir à travers l’art.</p>
      <p><strong>Valeurs :</strong> Passion, authenticité, dépassement de soi. On célèbre l’énergie brute et la créativité de chaque participant.</p>
      <p><strong>Équipe :</strong> Fondé par Dayio Blacksnake, un artiste inspiré par l’urbain et le pouvoir de la danse pour changer des vies, en collaboration avec Tamara Journeau, une personne qui amplifie les voix authentiques.</p>
      <p><strong>Pourquoi 20 places ?</strong> Un groupe restreint pour une expérience immersive, où chaque participant reçoit un accompagnement personnalisé.</p>
    </section>

    <!-- Footer -->
    <footer>
      <p>© 2025 Master Yourself</p>
      <p>
        <a href="https://www.instagram.com/dayio_blacksnake/">Instagram</a> | 
        <a href="https://www.facebook.com/wylcharles/">Facebook</a> | 
        <a href="https://www.threads.com/@dayio_blacksnake?xmt=AQGzIU3DONWCPO1hARAmR_cVsJkeEAvzVSkFasOb2LaKcZ8">Threads</a>
      </p>
    </footer>
  </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  // Données du formulaire
  const form = ref({
    full_name: '',
    age: null,
    email: '',
    instagram_tag: '',
    description: '',
    video: null,
    photo: null,
    cv: null,
  });
  
  // Références pour les inputs de fichiers
  const videoInput = ref(null);
  const photoInput = ref(null);
  const cvInput = ref(null);
  
  // Afficher le pop-up
  const goToSignup = () => {
    const signupPopup = document.getElementById('signupPopup');
    signupPopup.style.display = 'flex';
  };
  
  // Fermer le pop-up
  const closePopup = () => {
    const signupPopup = document.getElementById('signupPopup');
    signupPopup.style.display = 'none';
    resetForm();
  };
  
  // Gestion des fichiers uploadés
  const handleFileUpload = (field) => {
    if (field === 'photo') {
      form.value.photo = photoInput.value.files[0];
    } else if (field === 'cv') {
      form.value.cv = cvInput.value.files[0];
    }
  };
  
  // Réinitialiser le formulaire
  const resetForm = () => {
    form.value = {
      full_name: '',
      age: null,
      email: '',
      instagram_tag: '',
      description: '',
      video: null,
      photo: null,
      cv: null,
    };
    if (videoInput.value) videoInput.value.value = '';
    if (photoInput.value) photoInput.value.value = '';
    if (cvInput.value) cvInput.value.value = '';
  };
  
  // Soumission du formulaire
  const submitForm = async () => {
    const formData = new FormData();
    formData.append('full_name', form.value.full_name);
    formData.append('age', form.value.age);
    formData.append('email', form.value.email);
    if (form.value.instagram_tag) formData.append('instagram_tag', form.value.instagram_tag);
    if (form.value.description) formData.append('description', form.value.description);
    if (form.value.photo) formData.append('photo', form.value.photo);
    if (form.value.cv) formData.append('cv', form.value.cv);
  
    // Récupérer le token CSRF depuis la balise meta
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
    try {
      const response = await fetch('/api/participants', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken, // Ajouter le token CSRF dans les en-têtes
        },
        body: formData,
      });
  
      if (response.ok) {
        alert('Inscription réussie ! Merci de nous rejoindre.');
        closePopup();
      } else {
        const errorData = await response.json();
        alert('Erreur lors de l\'inscription : ' + (errorData.message || 'Veuillez réessayer.'));
      }
    } catch (error) {
      alert('Erreur réseau : ' + error.message);
    }
  };
  </script>
  
  <style scoped>
  /* Styles globaux pour la page */
  .home-page {
    font-family: 'Open Sans', sans-serif;
    background: url('/img/MasterYourselfFlyerDen.png') no-repeat center/cover fixed;
    color: #EDEDED;
    overflow-x: hidden;
    background-color: #2C2C2C;
    background-blend-mode: overlay;
  }
  
  /* Header */
  header {
    position: fixed;
    top: 0;
    width: 100%;
    background: rgba(44, 44, 44, 0.9);
    padding: 10px 20px;
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  header .logo {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #F4A261;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
  }
  
  header nav a {
    font-family: 'Montserrat', sans-serif;
    color: #D8DEE9;
    margin-left: 10px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
  }
  
  header nav a:hover,
  header nav a.active {
    color: #E9C46A;
  }
  
  /* Hero Section */
  .hero {
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 20px;
    position: relative;
  }
  
  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(0, 0, 0, 0.2) 0%, transparent 70%);
    z-index: 0;
  }
  
  .hero > * {
    position: relative;
    z-index: 1;
  }
  
  .hero h1 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: #F4A261;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    margin-bottom: 10px;
  }
  
  .hero h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 1.2rem;
    font-weight: 400;
    color: #E9C46A;
    margin-bottom: 15px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
  }
  
  .hero p {
    font-family: 'Open Sans', sans-serif;
    font-size: 0.9rem;
    color: #D8DEE9;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
    max-width: 800px;
  }
  
  .hero button {
    padding: 10px 20px;
    background: #264653;
    border: 2px solid #2A9D8F;
    color: #EDEDED;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  }
  
  .hero button:hover {
    background: #2A9D8F;
    transform: scale(1.05);
  }
  
  /* Styles pour le pop-up */
  .popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    justify-content: center;
    align-items: center;
  }
  
  .popup-content {
    background: #2C2C2C;
    padding: 30px;
    border-radius: 10px;
    max-width: 400px;
    width: 90%;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    border: 2px solid #F4A261;
    max-height: 80vh;
    overflow-y: auto;
  }
  
  .popup-content h2 {
    font-family: 'Bebas Neue', sans-serif;
    color: #F4A261;
    font-size: 2rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    margin-bottom: 20px;
    text-align: center;
  }
  
  .popup-content form {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  
  .popup-content label {
    font-family: 'Montserrat', sans-serif;
    color: #E9C46A;
    font-size: 1rem;
  }
  
  .popup-content input,
  .popup-content textarea {
    padding: 10px;
    background: #3B4252;
    border: 1px solid #2A9D8F;
    color: #EDEDED;
    font-family: 'Open Sans', sans-serif;
    font-size: 0.9rem;
    border-radius: 5px;
    outline: none;
  }
  
  .popup-content input[type="file"] {
    padding: 5px;
    background: none;
    border: none;
  }
  
  .popup-content textarea {
    resize: vertical;
  }
  
  .popup-content input::placeholder,
  .popup-content textarea::placeholder {
    color: #D8DEE9;
    opacity: 0.7;
  }
  
  .popup-content button {
    padding: 10px;
    background: #264653;
    border: 2px solid #2A9D8F;
    color: #EDEDED;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  }
  
  .popup-content button:hover {
    background: #2A9D8F;
    transform: scale(1.05);
  }
  
  .close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 1.5rem;
    color: #E9C46A;
    cursor: pointer;
    transition: color 0.3s ease;
  }
  
  .close:hover {
    color: #F4A261;
  }
  
  /* About Section */
  .about-section {
    padding: 20px;
    background: rgba(44, 44, 44, 0.8);
    position: relative;
  }
  
  .about-section h2 {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 1.5rem;
    color: #F4A261;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    text-align: center;
    margin-bottom: 15px;
  }
  
  .about-section p {
    font-family: 'Open Sans', sans-serif;
    font-size: 0.9rem;
    color: #D8DEE9;
    margin-bottom: 10px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
  }
  
  /* Footer */
  footer {
    background: #2C2C2C;
    padding: 15px 20px;
    text-align: center;
  }
  
  footer p {
    font-family: 'Open Sans', sans-serif;
    font-size: 0.8rem;
    color: #D8DEE9;
  }
  
  footer a {
    font-family: 'Montserrat', sans-serif;
    color: #E9C46A;
    text-decoration: none;
    margin: 0 5px;
    transition: color 0.3s ease;
  }
  
  footer a:hover {
    color: #2A9D8F;
  }
  
  /* Responsive Design for Desktop */
  @media (min-width: 768px) {
    header {
      padding: 20px 40px;
    }
  
    header .logo {
      font-size: 2rem;
    }
  
    header nav a {
      font-size: 1.1rem;
      margin-left: 20px;
    }
  
    .hero {
      padding: 40px;
    }
  
    .hero h1 {
      font-size: 4rem;
    }
  
    .hero h2 {
      font-size: 1.8rem;
    }
  
    .hero p {
      font-size: 1.2rem;
      max-width: 800px;
    }
  
    .hero button {
      padding: 15px 30px;
      font-size: ████████;
    }
    .popup-content {
      max-width: 500px;
    }
  
    .about-section {
      padding: 40px;
      max-width: 800px;
      margin: 0 auto;
    }
  
    .about-section h2 {
      font-size: 2rem;
    }
  
    .about-section p {
      font-size: 1.1rem;
    }
  
    footer {
      padding: 20px 40px;
    }
  
    footer p {
      font-size: 1rem;
    }
  }
  </style>