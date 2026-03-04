import React, { useState, useEffect } from 'react'
import axios from 'axios'
import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'

export default function UserManagement() {
    const { user, logout } = useAuth()
    const [users, setUsers] = useState([])
    const [showModal, setShowModal] = useState(false)
    const [form, setForm] = useState({ name: '', email: '', password: '', role: 'employee' })
    const [editId, setEditId] = useState(null)

    useEffect(() => {
        loadUsers()
    }, [])

    const loadUsers = () => {
        axios.get('/api/users').then(res => setUsers(res.data))
    }

    const handleSubmit = async (e) => {
        e.preventDefault()
        if (editId) {
            await axios.put(`/api/users/${editId}`, form)
        } else {
            await axios.post('/api/users', form)
        }
        setShowModal(false)
        setForm({ name: '', email: '', password: '', role: 'employee' })
        setEditId(null)
        loadUsers()
    }

    const handleEdit = (u) => {
        setForm({ name: u.name, email: u.email, password: '', role: u.role })
        setEditId(u.id)
        setShowModal(true)
    }

    const handleDelete = async (id) => {
        if (confirm('Delete this user?')) {
            await axios.delete(`/api/users/${id}`)
            loadUsers()
        }
    }

    return (
        <div className="min-h-screen bg-gray-50">
            <nav className="bg-white border-b px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-50 h-16">
                <h1 className="text-2xl font-semibold">
                    <i className="fas fa-users text-red-600 mr-2"></i>User Management
                </h1>
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

            <div className="fixed left-0 top-16 w-64 h-[calc(100vh-4rem)] bg-white border-r pt-6">
                <ul className="px-4">
                    <li className="mb-1">
                        <Link to="/dashboard" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">
                            <i className="fas fa-home w-5"></i> Dashboard
                        </Link>
                    </li>
                    <li className="mb-1">
                        <Link to="/users" className="flex items-center gap-3 px-4 py-3 rounded-lg bg-red-600 text-white text-sm font-medium">
                            <i className="fas fa-users w-5"></i> Users
                        </Link>
                    </li>
                    <li className="mb-1">
                        <Link to="/work-settings" className="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-500 hover:bg-gray-100 text-sm font-medium">
                            <i className="fas fa-clock w-5"></i> Work Schedule
                        </Link>
                    </li>
                </ul>
            </div>

            <div className="ml-64 mt-16 p-8">
                <div className="bg-white border rounded-xl p-6">
                    <div className="flex justify-between items-center mb-6">
                        <h2 className="text-xl font-semibold">All Users</h2>
                        <button onClick={() => setShowModal(true)} className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm font-medium">
                            <i className="fas fa-plus"></i> Add User
                        </button>
                    </div>
                    <table className="w-full">
                        <thead>
                            <tr className="bg-gray-50 border-b">
                                <th className="px-4 py-3 text-left text-sm font-semibold text-gray-600">Name</th>
                                <th className="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                                <th className="px-4 py-3 text-left text-sm font-semibold text-gray-600">Role</th>
                                <th className="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {users.map(u => (
                                <tr key={u.id} className="border-b">
                                    <td className="px-4 py-3 text-sm">{u.name}</td>
                                    <td className="px-4 py-3 text-sm">{u.email}</td>
                                    <td className="px-4 py-3 text-sm">
                                        <span className={`px-3 py-1 rounded-full text-xs font-medium ${u.role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'}`}>
                                            {u.role}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-sm">
                                        <button onClick={() => handleEdit(u)} className="text-blue-600 hover:text-blue-800 mr-3">
                                            <i className="fas fa-edit"></i>
                                        </button>
                                        <button onClick={() => handleDelete(u.id)} className="text-red-600 hover:text-red-800">
                                            <i className="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg p-6 w-96">
                        <h3 className="text-xl font-semibold mb-4">{editId ? 'Edit User' : 'Add User'}</h3>
                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" value={form.email} onChange={e => setForm({...form, email: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required />
                            </div>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">Password {editId && '(leave blank to keep current)'}</label>
                                <input type="password" value={form.password} onChange={e => setForm({...form, password: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500" required={!editId} />
                            </div>
                            <div className="mb-6">
                                <label className="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select value={form.role} onChange={e => setForm({...form, role: e.target.value})} className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="employee">Employee</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div className="flex gap-3">
                                <button type="submit" className="flex-1 bg-red-600 text-white py-2 rounded hover:bg-red-700">Save</button>
                                <button type="button" onClick={() => { setShowModal(false); setEditId(null); setForm({ name: '', email: '', password: '', role: 'employee' }); }} className="flex-1 bg-gray-300 text-gray-700 py-2 rounded hover:bg-gray-400">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    )
}
