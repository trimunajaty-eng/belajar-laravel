import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'

export default function WorkSettings() {
    const { user, logout } = useAuth()
    const [settings, setSettings] = useState({ work_start_time: '08:00', late_threshold: '09:00', work_end_time: '17:00' })

    useEffect(() => {
        axios.get('/api/work-settings').then(res => setSettings(res.data))
    }, [])

    const handleSubmit = async (e) => {
        e.preventDefault()
        await axios.post('/api/work-settings', settings)
        alert('Settings updated successfully!')
    }

    return (
        <div className="min-h-screen bg-gray-50">
            <nav className="bg-white border-b px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-50 h-16">
                <h1 className="text-2xl font-semibold"><i className="fas fa-clock text-red-600 mr-2"></i>Work Settings</h1>
                <div className="flex items-center gap-4">
                    <div className="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-medium text-sm">{user.name[0]}</div>
                    <span className="font-medium text-gray-600">{user.name}</span>
                    <button onClick={logout} className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm font-medium">
                        <i className="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </nav>

            <div className="fixed left-0 top-16 w-64 h-[calc(100vh-4rem)] bg-white border-r pt-6">
                <ul className="px-4">
                    <li className="mb-1"><Link to="/dashboard" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium"><i className="fas fa-home w-5"></i> Dashboard</Link></li>
                    <li className="mb-1"><Link to="/users" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium"><i className="fas fa-users w-5"></i> Users</Link></li>
                    <li className="mb-1"><Link to="/work-settings" className="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 text-white text-sm font-medium"><i className="fas fa-clock w-5"></i> Work Schedule</Link></li>
                </ul>
            </div>

            <div className="ml-64 mt-16 p-8">
                <div className="bg-white border rounded-xl p-6 max-w-2xl">
                    <h2 className="text-xl font-semibold mb-6">Configure Work Schedule</h2>
                    <form onSubmit={handleSubmit}>
                        <div className="mb-4">
                            <label className="block text-sm font-medium text-gray-700 mb-2">Work Start Time</label>
                            <input type="time" value={settings.work_start_time} onChange={e => setSettings({...settings, work_start_time: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required />
                        </div>
                        <div className="mb-4">
                            <label className="block text-sm font-medium text-gray-700 mb-2">Late Threshold</label>
                            <input type="time" value={settings.late_threshold} onChange={e => setSettings({...settings, late_threshold: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required />
                        </div>
                        <div className="mb-6">
                            <label className="block text-sm font-medium text-gray-700 mb-2">Work End Time</label>
                            <input type="time" value={settings.work_end_time} onChange={e => setSettings({...settings, work_end_time: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required />
                        </div>
                        <button type="submit" className="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 font-medium">
                            <i className="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    )
}
