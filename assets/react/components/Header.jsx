import React from 'react';
import { FaUserCircle, FaSignOutAlt, FaUsers } from 'react-icons/fa';

const Header = () => {
  const handleLogout = async () => {
    try {
      const response = await fetch('/deconnexion', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
      });

      if (response.ok) {
        // Redirection vers la page de login
        window.location.href = '/login';
      } else {
        console.error('Erreur lors de la déconnexion');
      }
    } catch (error) {
      console.error('Erreur:', error);
    }
  };

  return (
    <header 
      className="
        fixed top-0 right-0 left-0 z-20
        bg-[rgb(27,63,104)] h-16 shadow-md
        border-b border-white/10
      "
    >
      <div className="flex justify-end items-center h-full px-6">
        <div className="flex items-center space-x-4">
          <button 
            className="text-white hover:bg-blue-900 p-2 rounded-full transition-colors duration-200"
            title="Intervenants"
          >
            <FaUsers size={24} />
          </button>

          <div className="w-px h-6 bg-white/20" />
          
          <button 
            className="text-white hover:bg-blue-900 p-2 rounded-full transition-colors duration-200"
            title="Mon compte"
          >
            <FaUserCircle size={24} />
          </button>
          
          <div className="w-px h-6 bg-white/20" />
          
          <button 
            onClick={handleLogout}
            className="text-white hover:bg-red-500 p-2 rounded-full transition-colors duration-200"
            title="Déconnexion"
          >
            <FaSignOutAlt size={24} />
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;