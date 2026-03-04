import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'

export default function AdminDashboard() {
    const { user, logout } = useAuth()
    const [data, setData] = useState(null)

    useEffect(() => {
        axios.get('/api/dashboard').then(res => setData(res.data))
    }, [])

    if (!data) return <div>Loading...</div>

    return (
        <div className="min-h-screen bg-gray-50">
            <nav className="bg-white border-b px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-50 h-16">
                <h1 className="text-2xl font-semibold"><i className="fas fa-chart-line text-red-600 mr-2"></i>Admin Dashboard</h1>
                <div className="flex items-center gap-4">
                    <div className="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center text-white font-medium text-sm">
                        {user.name[0]}
                    </div>
                    <span className="font-medium text-gray-600">{user.name}</span>
                    <button onClick={logout} className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm font-medium">
                        <i className="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </nav>

            <div className="fixed left-0 top-16 w-64 h-[calc(100vh-4rem)] bg-white border-r pt-6 overflow-y-auto">
                <ul className="px-4">
                    <li className="mb-1"><Link to="/dashboard" className="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 text-white text-sm font-medium"><i className="fas fa-home w-5"></i> Dashboard</Link></li>
                    <li className="mb-1"><Link to="/users" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium"><i className="fas fa-users w-5"></i> Users</Link></li>
                    <li className="mb-1"><Link to="/work-settings" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium"><i className="fas fa-clock w-5"></i> Work Schedule</Link></li>
                </ul>
            </div>

            <div className="ml-64 mt-16 p-8">
                <div className="bg-white border rounded-xl p-8 mb-8">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-2">Welcome back, {user.name}!</h2>
                    <p>Today is {new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })} - Monitor your team's attendance</p>
                </div>

                <div className="grid grid-cols-4 gap-6 mb-8">
                    <div className="bg-white border rounded-xl p-6 hover:shadow-md transition">
                        <div className="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-lg mb-4"><i className="fas fa-users"></i></div>
                        <div className="text-3xl font-bold text-gray-800 mb-1">{data.totalEmployees}</div>
                        <div className="text-sm font-medium text-gray-500">Total Employees</div>
                    </div>
                    <div className="bg-white border rounded-xl p-6 hover:shadow-md transition">
                        <div className="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center text-lg mb-4"><i className="fas fa-check-circle"></i></div>
                        <div className="text-3xl font-bold text-gray-800 mb-1">{data.presentToday}</div>
                        <div className="text-sm font-medium text-gray-500">Present Today</div>
                    </div>
                    <div className="bg-white border rounded-xl p-6 hover:shadow-md transition">
                        <div className="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center text-lg mb-4"><i className="fas fa-clock"></i></div>
                        <div className="text-3xl font-bold text-gray-800 mb-1">{data.lateToday}</div>
                        <div className="text-sm font-medium text-gray-500">Late Today</div>
                    </div>
                    <div className="bg-white border rounded-xl p-6 hover:shadow-md transition">
                        <div className="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-lg mb-4"><i className="fas fa-times-circle"></i></div>
                        <div className="text-3xl font-bold text-gray-800 mb-1">{data.absentToday}</div>
                        <div className="text-sm font-medium text-gray-500">Absent Today</div>
                    </div>
                </div>

                <div className="bg-white border rounded-xl p-6 mb-8">
                    <h3 className="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2"><i className="fas fa-cog"></i> Work Time Settings</h3>
                    <div className="grid grid-cols-3 gap-4">
                        <div className="text-center p-4 bg-gray-50 rounded-lg">
                            <div className="text-sm text-gray-500 mb-2">Work Start Time</div>
                            <div className="text-lg font-semibold text-gray-800">{data.workSetting?.work_start_time || '08:00'}</div>
                        </div>
                        <div className="text-center p-4 bg-gray-50 rounded-lg">
                            <div className="text-sm text-gray-500 mb-2">Late Threshold</div>
                            <div className="text-lg font-semibold text-gray-800">{data.workSetting?.late_threshold || '09:00'}</div>
                        </div>
                        <div className="text-center p-4 bg-gray-50 rounded-lg">
                            <div className="text-sm text-gray-500 mb-2">Work End Time</div>
                            <div className="text-lg font-semibold text-gray-800">{data.workSetting?.work_end_time || '17:00'}</div>
                        </div>
                    </div>
                    <div className="mt-4">
                        <Link to="/work-settings" className="inline-block px-4 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700">
                            <i className="fas fa-edit"></i> Edit Schedule
                        </Link>
                    </div>
                </div>

                <div className="bg-white border rounded-xl p-6">
                    <h3 className="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2"><i className="fas fa-calendar-check"></i> Today's Attendance</h3>
                    <table className="w-full">
                        <thead>
                            <tr className="bg-gray-50 border-b">
                                <th className="px-3 py-3 text-left text-sm font-semibold text-gray-600">Employee</th>
                                <th className="px-3 py-3 text-left text-sm font-semibold text-gray-600">Check In</th>
                                <th className="px-3 py-3 text-left text-sm font-semibold text-gray-600">Check Out</th>
                                <th className="px-3 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.todayAttendances?.map(att => (
                                <tr key={att.id} className="border-b">
                                    <td className="px-3 py-3 text-sm">
                                        <strong>{att.user.name}</strong><br/>
                                        <small className="text-gray-500">{att.user.email}</small>
                                    </td>
                                    <td className="px-3 py-3 text-sm">
                                        <span className={att.status === 'late' ? 'text-red-600 font-semibold' : 'text-green-600'}>
                                            {att.check_in || 'Not checked in'}
                                        </span>
                                    </td>
                                    <td className="px-3 py-3 text-sm">{att.check_out || <span className="text-blue-600 font-medium">Still working</span>}</td>
                                    <td className="px-3 py-3 text-sm">
                                        <span className={`px-3 py-1 rounded-full text-xs font-medium ${
                                            att.status === 'present' ? 'bg-green-100 text-green-800' :
                                            att.status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'
                                        }`}>
                                            {att.status}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                            {data.absentEmployees?.map(emp => (
                                <tr key={emp.id} className="bg-red-50 border-b">
                                    <td className="px-3 py-3 text-sm">
                                        <strong>{emp.name}</strong><br/>
                                        <small className="text-gray-500">{emp.email}</small>
                                    </td>
                                    <td className="px-3 py-3 text-sm text-red-600">Not checked in</td>
                                    <td className="px-3 py-3 text-sm text-gray-500">-</td>
                                    <td className="px-3 py-3 text-sm">
                                        <span className="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Absent</span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    )
}
