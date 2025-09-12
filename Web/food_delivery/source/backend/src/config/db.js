import 'dotenv/config'
import mongoose from "mongoose";

const dbState = [{
    value: 0,
    label: "Disconnected"
},
{
    value: 1,
    label: "Connected"
},
{
    value: 2,
    label: "Connecting"
},
{
    value: 3,
    label: "Disconnecting"
}];

const connection = async () => {
    const options = {
        dbName: process.env.DB_NAME,
    }
    // Only set auth if both user and password are provided
    if (process.env.DB_USER && process.env.DB_PASSWORD) {
        options.user = process.env.DB_USER
        options.pass = process.env.DB_PASSWORD
    }
    try {
        await mongoose.connect(process.env.DB_HOST, options);
        const state = Number(mongoose.connection.readyState);
        console.log(dbState.find(f => f.value === state).label, "to database"); // connected to db
    } catch (error) {
        console.log(">>>>error occer when connect to db", error)
        throw error
    }
}

export default connection