import React, { useState } from "react";
import { Sidebar, Menu, MenuItem } from "react-pro-sidebar";
import { FaProjectDiagram, FaSearch, FaUsers } from "react-icons/fa";
import logo from "../../../public/images/logo.png";
import logo2 from "../../../public/images/logo2.png";
import '../../styles/app.css';  

const Barre = () => {
    const [collapsed, setCollapsed] = useState(false);
    const [activeSection, setActiveSection] = useState(null);

    const menuItems = [
        { id: 1, label: 'Tableau de bord', icon: <FaProjectDiagram />, section: 'projects', path: '/projet' },
        { id: 2, label: 'Gestion GPRO', icon: <FaSearch />, section: 'search' },
        { id: 3, label: 'Statistiques', icon: <FaUsers />, section: 'users' }
    ];

    const handleSectionClick = (section, path) => {
        setActiveSection(section);
        if (path) {
            window.location.href = path;
        }
    };

    const toggleCollapse = () => {
        setCollapsed(!collapsed);
    };

    // Ajoutez une classe globale au body pour gérer le padding du contenu
    document.body.classList.toggle('sidebar-collapsed', collapsed);
    document.body.classList.toggle('sidebar-expanded', !collapsed);

    return (
        <div className="fixed left-0 top-16 bottom-0 z-10 transition-all duration-300 ease-in-out">
            <Sidebar
                collapsed={collapsed}
                className="h-full"
                width={collapsed ? "80px" : "250px"}
                transitionDuration={300}
                backgroundColor="#0077C2"
                style={{ borderRadius: '8px' }}
            >
                <div className="flex justify-center items-center">
                    {collapsed ? (
                        <img
                            src={logo2}
                            alt="Logo"
                            className="h-12 w-12 mt-4 transition-all duration-300 ease-in-out object-contain cursor-pointer"
                            onClick={toggleCollapse}
                        />
                    ) : (
                        <img
                            src={logo}
                            alt="Logo"
                            className="h-24 w-auto transition-all duration-300 ease-in-out object-contain cursor-pointer"
                            onClick={toggleCollapse}
                        />
                    )}
                </div>

                <Menu
      className={`pt-4 ${collapsed ? 'flex flex-col h-[calc(100%-80px)]' : 'pt-4'}`}
      menuItemStyles={{
        button: {
          color: '#ffffff',
          fontWeight: 500,
          paddingLeft: '16px',
          [`&:hover`]: {
            backgroundColor: 'rgb(27, 63, 104)',
            borderRadius: '4px',
            margin: '0 4px',
          },
          [`&.ps-active`]: {
            backgroundColor: 'rgb(27, 63, 104)',
            borderRadius: '4px',
            margin: '0 4px',
          },
        },
        icon: {
          color: '#ffffff',
        },
      }}
    >
      {menuItems.map((item) => (
        <MenuItem
          key={item.id}
          icon={item.icon}
          active={activeSection === item.section}
          onClick={() => handleSectionClick(item.section, item.path)}
          className={`my-1 ${collapsed ? 'flex-grow' : ''}`}
        >
          {item.label}
        </MenuItem>
      ))}
    </Menu>
            </Sidebar>
        </div>
    );
};

export default Barre;