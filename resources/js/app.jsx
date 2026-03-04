import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import Login from './pages/Login';
import AdminDashboard from './pages/AdminDashboard';
import EmployeeDashboard from './pages/EmployeeDashboard';
import UserManagement from './pages/UserManagement';
import WorkSettings from './pages/WorkSettings';
import { AuthProvider, useAuth } from './context/AuthContext';

// Protected Route Component
function ProtectedRoute({ children, requireAdmin = false }) {
    const { user, loading } = useAuth();
    
    if (loading) return <div>Loading...</div>;
    
    if (!user) return <Navigate to="/login" />;
    
    if (requireAdmin && user.role !== 'admin') {
        return <Navigate to="/dashboard" />;
    }
    
    return children;
}

function App() {
    return (
        <AuthProvider>
            <BrowserRouter>
                <Routes>
                    <Route path="/login" element={<Login />} />
                    <Route 
                        path="/dashboard" 
                        element={
                            <ProtectedRoute>
                                <DashboardRouter />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/users" 
                        element={
                            <ProtectedRoute requireAdmin>
                                <UserManagement />
                            </ProtectedRoute>
                        } 
                    />
                    <Route 
                        path="/work-settings" 
                        element={
                            <ProtectedRoute requireAdmin>
                                <WorkSettings />
                            </ProtectedRoute>
                        } 
                    />
                    <Route path="/" element={<Navigate to="/dashboard" />} />
                </Routes>
            </BrowserRouter>
        </AuthProvider>
    );
}

function DashboardRouter() {
    const { user } = useAuth();
    return user?.role === 'admin' ? <AdminDashboard /> : <EmployeeDashboard />;
}

export default App;