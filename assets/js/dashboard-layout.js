import React from 'react';
import { createRoot } from 'react-dom/client';
import Barre from '../react/components/Barre';
import Header from '../react/components/Header';

document.addEventListener('DOMContentLoaded', () => {
    const dashboardContainer = document.getElementById('react-dashboard');
    
    if (dashboardContainer) {
        // État partagé pour le collapsed
        const isCollapsed = false;

        // Monter la Sidebar
        const sidebarContainer = document.getElementById('sidebar-container');
        if (sidebarContainer) {
            const sidebarRoot = createRoot(sidebarContainer);
            sidebarRoot.render(
                <React.StrictMode>
                    <Barre onCollapse={(collapsed) => {
                        // Forcer la mise à jour du header quand la sidebar change
                        headerRoot.render(
                            <React.StrictMode>
                                <Header collapsed={collapsed} />
                            </React.StrictMode>
                        );
                    }} />
                </React.StrictMode>
            );
        }

        // Monter le Header
        const headerContainer = document.getElementById('header-container');
        if (headerContainer) {
            const headerRoot = createRoot(headerContainer);
            headerRoot.render(
                <React.StrictMode>
                    <Header collapsed={isCollapsed} />
                </React.StrictMode>
            );
        }
    }
});